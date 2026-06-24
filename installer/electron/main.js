const { app, BrowserWindow, Tray, Menu, shell, nativeImage, dialog, globalShortcut } = require('electron');
const { spawn } = require('child_process');
const path = require('path');
const fs = require('fs');
const http = require('http');

// ── Paths ────────────────────────────────────────────────────────────────────
const IS_PACKAGED = app.isPackaged;
const RES = IS_PACKAGED
  ? path.join(process.resourcesPath)
  : path.join(__dirname, 'dev-resources');

const PHP_EXE    = path.join(RES, 'php', 'php.exe');
const MYSQL_BIN  = path.join(RES, 'mariadb', 'bin');
const LARAVEL    = path.join(RES, 'laravel');
const USER_DATA  = app.getPath('userData');           // AppData/Roaming/QPOS
const DATA_DIR   = path.join(USER_DATA, 'db-data');
const MY_INI     = path.join(USER_DATA, 'my.ini');
const ENV_FILE   = path.join(LARAVEL, '.env');
const ENV_PROD   = path.join(LARAVEL, '.env.production');
const ASSETS     = path.join(__dirname, 'assets');

const PORT    = 8000;
const DB_PORT = 3306;
const DB_PASS = 'qpos_secret';
const APP_URL = `http://127.0.0.1:${PORT}`;

// ── State ────────────────────────────────────────────────────────────────────
let phpProc   = null;
let dbProc    = null;
let mainWin   = null;
let splashWin = null;
let tray      = null;
let isQuitting = false;

// ── App single-instance lock ─────────────────────────────────────────────────
if (!app.requestSingleInstanceLock()) {
  app.quit();
  process.exit(0);
}
app.on('second-instance', () => {
  if (mainWin) { mainWin.show(); mainWin.focus(); }
});

// ── Helpers ──────────────────────────────────────────────────────────────────
function log(msg) {
  const ts = new Date().toISOString();
  const logPath = path.join(USER_DATA, 'qpos.log');
  fs.appendFileSync(logPath, `[${ts}] ${msg}\n`);
  console.log(msg);
}

function sendStatus(msg) {
  log(msg);
  if (splashWin && !splashWin.isDestroyed()) {
    splashWin.webContents.send('status', msg);
  }
}

function writeMyIni() {
  const ini = [
    '[mysqld]',
    `datadir=${DATA_DIR.replace(/\\/g, '/')}`,
    `port=${DB_PORT}`,
    'innodb_buffer_pool_size=64M',
    'max_connections=20',
    'character-set-server=utf8mb4',
    'collation-server=utf8mb4_unicode_ci',
    'skip-networking=0',
    '[client]',
    `port=${DB_PORT}`,
  ].join('\r\n');
  fs.writeFileSync(MY_INI, ini, 'utf8');
}

// ── Wait for DB to accept connections ────────────────────────────────────────
function waitForDB(retries = 30) {
  return new Promise((resolve, reject) => {
    const mysqlExe = path.join(MYSQL_BIN, 'mysql.exe');
    let attempts = 0;
    const check = () => {
      const p = spawn(mysqlExe, ['-u', 'root', `--password=${DB_PASS}`, '--connect-timeout=2', '-e', 'SELECT 1'], { windowsHide: true });
      p.on('close', (code) => {
        if (code === 0) return resolve();
        attempts++;
        if (attempts >= retries) return reject(new Error('Database did not start in time.'));
        setTimeout(check, 1000);
      });
    };
    check();
  });
}

// ── Wait for PHP web server ──────────────────────────────────────────────────
function waitForWeb(retries = 30) {
  return new Promise((resolve, reject) => {
    let attempts = 0;
    const check = () => {
      http.get(APP_URL, (res) => {
        resolve();
      }).on('error', () => {
        attempts++;
        if (attempts >= retries) return reject(new Error('Web server did not start in time.'));
        setTimeout(check, 1000);
      });
    };
    setTimeout(check, 1000);
  });
}

// ── Fix auth plugin so PHP pdo_mysql can connect ─────────────────────────────
function fixAuthPlugin() {
  return new Promise((resolve) => {
    const mysqlExe = path.join(MYSQL_BIN, 'mysql.exe');
    const sql = `ALTER USER 'root'@'localhost' IDENTIFIED VIA mysql_native_password USING PASSWORD('${DB_PASS}'); FLUSH PRIVILEGES;`;
    const p = spawn(mysqlExe, ['-u', 'root', `--password=${DB_PASS}`, '--connect-timeout=5', '-e', sql], { windowsHide: true });
    p.on('close', (code) => { log('fixAuthPlugin exit: ' + code); resolve(); });
    p.on('error', () => resolve()); // non-fatal
  });
}

// ── Initialize MariaDB data directory ────────────────────────────────────────
function initDB() {
  return new Promise((resolve, reject) => {
    const mysqlDir = path.join(DATA_DIR, 'mysql');
    if (fs.existsSync(mysqlDir)) return resolve(false); // already initialized

    fs.mkdirSync(DATA_DIR, { recursive: true });

    const initExe = path.join(MYSQL_BIN, 'mariadb-install-db.exe');
    const p = spawn(initExe, [`--datadir=${DATA_DIR}`, `--password=${DB_PASS}`], {
      windowsHide: true,
    });
    p.on('close', (code) => {
      if (code === 0) resolve(true);
      else reject(new Error(`DB init failed with code ${code}`));
    });
  });
}

// ── Start MariaDB ─────────────────────────────────────────────────────────────
function startDB() {
  return new Promise((resolve, reject) => {
    const mysqldExe = path.join(MYSQL_BIN, 'mysqld.exe');
    dbProc = spawn(mysqldExe, [`--defaults-file=${MY_INI}`], {
      windowsHide: true,
      detached: false,
    });
    dbProc.on('error', reject);
    // Give it a moment then check connectivity
    setTimeout(() => waitForDB().then(resolve).catch(reject), 2000);
  });
}

// ── Create database + run first-time migrations ────────────────────────────
function firstTimeSetup() {
  return new Promise((resolve, reject) => {
    const mysqlExe = path.join(MYSQL_BIN, 'mysql.exe');

    // Create database
    const p = spawn(mysqlExe, [
      '-u', 'root', `--password=${DB_PASS}`,
      '-e', 'CREATE DATABASE IF NOT EXISTS electronics_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;'
    ], { windowsHide: true });

    p.on('close', (code) => {
      if (code !== 0) return reject(new Error('Failed to create database'));

      // Write .env if not exists
      if (fs.existsSync(ENV_PROD)) {
        fs.copyFileSync(ENV_PROD, ENV_FILE); // always overwrite to ensure correct production settings
      }

      // Run artisan migrate + seed
      const migrate = spawn(PHP_EXE, [path.join(LARAVEL, 'artisan'), 'migrate', '--force'], {
        windowsHide: true, cwd: LARAVEL,
      });
      migrate.on('close', (c) => {
        if (c !== 0) return reject(new Error('Migration failed'));
        const seed = spawn(PHP_EXE, [path.join(LARAVEL, 'artisan'), 'db:seed', '--force'], {
          windowsHide: true, cwd: LARAVEL,
        });
        seed.on('close', (s) => {
          if (s !== 0) return reject(new Error('Seeding failed'));
          // storage:link
          spawn(PHP_EXE, [path.join(LARAVEL, 'artisan'), 'storage:link'], {
            windowsHide: true, cwd: LARAVEL,
          }).on('close', () => resolve());
        });
      });
    });
  });
}

// ── Ensure all Laravel storage dirs exist and are writable ───────────────────
function ensureStorageDirs() {
  const dirs = [
    'storage/framework/cache/data',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
    'storage/app/public',
  ];
  for (const d of dirs) {
    fs.mkdirSync(path.join(LARAVEL, d), { recursive: true });
  }
  // Run optimize:clear to rebuild cached paths
  const p = require('child_process').spawnSync(PHP_EXE, [
    path.join(LARAVEL, 'artisan'), 'optimize:clear'
  ], { windowsHide: true, cwd: LARAVEL });
  log('optimize:clear exit: ' + p.status);
}

// ── Start PHP built-in server ─────────────────────────────────────────────────
function startPHP() {
  return new Promise((resolve, reject) => {
    phpProc = spawn(PHP_EXE, [
      '-S', `127.0.0.1:${PORT}`,
      '-t', path.join(LARAVEL, 'public'),
      path.join(LARAVEL, 'server.php'),
    ], {
      windowsHide: true,
      cwd: LARAVEL,
    });
    phpProc.on('error', reject);
    waitForWeb().then(resolve).catch(reject);
  });
}

// ── Stop all servers ──────────────────────────────────────────────────────────
function stopAll() {
  log('Stopping all services...');
  if (phpProc) { try { phpProc.kill('SIGTERM'); } catch (_) {} phpProc = null; }
  if (dbProc)  { try { dbProc.kill('SIGTERM');  } catch (_) {} dbProc  = null; }
  // Force-kill just in case
  try { require('child_process').execSync('taskkill /F /IM mysqld.exe /T', { windowsHide: true }); } catch (_) {}
}

// ── Splash window ─────────────────────────────────────────────────────────────
function createSplash() {
  splashWin = new BrowserWindow({
    width: 480,
    height: 320,
    frame: false,
    transparent: true,
    resizable: false,
    alwaysOnTop: true,
    center: true,
    webPreferences: {
      nodeIntegration: false,
      contextIsolation: true,
      preload: path.join(__dirname, 'preload.js'),
    },
  });
  splashWin.loadFile(path.join(__dirname, 'splash.html'));
}

// ── Main window ───────────────────────────────────────────────────────────────
function createMain() {
  mainWin = new BrowserWindow({
    width: 1280,
    height: 820,
    minWidth: 900,
    minHeight: 600,
    show: false,
    title: 'QPOS — Alkyne Solutions',
    icon: path.join(ASSETS, 'icon.png'),
    webPreferences: {
      nodeIntegration: false,
      contextIsolation: true,
    },
  });

  mainWin.loadURL(APP_URL);

  mainWin.once('ready-to-show', () => {
    if (splashWin && !splashWin.isDestroyed()) splashWin.destroy();
    mainWin.show();
    mainWin.focus();
  });

  // ── Inject floating Back + Reload toolbar into every page ──────────────────
  const TOOLBAR_CSS = `
    #qpos-toolbar {
      position: fixed; bottom: 18px; right: 18px; z-index: 2147483647;
      display: flex; gap: 8px; align-items: center;
    }
    .qpos-tb-btn {
      width: 38px; height: 38px; border-radius: 50%; border: none;
      cursor: pointer; font-size: 17px; font-weight: bold;
      box-shadow: 0 3px 10px rgba(0,0,0,.35);
      display: flex; align-items: center; justify-content: center;
      transition: transform .12s, opacity .12s;
      opacity: .75;
    }
    .qpos-tb-btn:hover { opacity: 1; transform: scale(1.12); }
    #qpos-back-btn  { background: #2c3e50; color: #fff; }
    #qpos-fwd-btn   { background: #2c3e50; color: #fff; }
    #qpos-reload-btn{ background: #e94560; color: #fff; }
    #qpos-busy-overlay {
      position: fixed; inset: 0; z-index: 2147483646;
      background: rgba(255,255,255,.92);
      display: flex; flex-direction: column;
      align-items: center; justify-content: center; gap: 16px;
    }
    #qpos-busy-overlay h2 { font-family: Segoe UI,sans-serif; color:#333; font-size:22px; }
    #qpos-busy-overlay p  { font-family: Segoe UI,sans-serif; color:#666; font-size:14px; }
    #qpos-busy-btn {
      padding: 10px 28px; background: #e94560; color: #fff;
      border: none; border-radius: 6px; font-size: 15px;
      font-weight: 600; cursor: pointer; font-family: Segoe UI,sans-serif;
    }
    #qpos-busy-btn:hover { background: #c73652; }
  `;

  const TOOLBAR_JS = `(function() {
    if (document.getElementById('qpos-toolbar')) return;
    const bar = document.createElement('div');
    bar.id = 'qpos-toolbar';

    const mk = (id, title, html, fn) => {
      const b = document.createElement('button');
      b.id = id; b.className = 'qpos-tb-btn';
      b.title = title; b.innerHTML = html;
      b.addEventListener('click', fn);
      return b;
    };

    const back = mk('qpos-back-btn',   'Go Back  (Alt + ←)',    '&#8592;', () => history.back());
    const fwd  = mk('qpos-fwd-btn',    'Go Forward (Alt + →)',  '&#8594;', () => history.forward());
    const rel  = mk('qpos-reload-btn', 'Reload Page (F5)',       '&#8635;', () => { removeBusy(); location.reload(); });

    bar.appendChild(back);
    bar.appendChild(fwd);
    bar.appendChild(rel);
    document.body.appendChild(bar);
  })();`;

  // Show busy overlay if a navigation takes longer than 8 seconds
  const BUSY_SHOW_JS = `(function() {
    if (document.getElementById('qpos-busy-overlay')) return;
    const ov = document.createElement('div');
    ov.id = 'qpos-busy-overlay';
    ov.innerHTML = '<h2>&#9203; System is busy&hellip;</h2>' +
      '<p>The server is processing your previous action. Please wait or reload.</p>' +
      '<button id="qpos-busy-btn" onclick="this.closest(\'#qpos-busy-overlay\').remove(); location.reload();">Reload Now</button>';
    document.body.appendChild(ov);
  })();`;

  const BUSY_HIDE_JS = `(function(){var o=document.getElementById('qpos-busy-overlay');if(o)o.remove();})();`;

  let navTimer = null;

  mainWin.webContents.on('did-start-navigation', (e, url, isInPage) => {
    if (isInPage) return; // anchor links — ignore
    if (navTimer) clearTimeout(navTimer);
    navTimer = setTimeout(() => {
      if (mainWin && !mainWin.isDestroyed()) {
        mainWin.webContents.executeJavaScript(BUSY_SHOW_JS).catch(() => {});
      }
    }, 8000);
  });

  mainWin.webContents.on('did-finish-load', () => {
    if (navTimer) { clearTimeout(navTimer); navTimer = null; }
    if (!mainWin || mainWin.isDestroyed()) return;
    mainWin.webContents.insertCSS(TOOLBAR_CSS).catch(() => {});
    mainWin.webContents.executeJavaScript(TOOLBAR_JS).catch(() => {});
    mainWin.webContents.executeJavaScript(BUSY_HIDE_JS).catch(() => {});
  });

  mainWin.webContents.on('did-fail-load', (_e, code, desc) => {
    if (navTimer) { clearTimeout(navTimer); navTimer = null; }
    if (code === -3) return; // aborted navigation — user clicked something else
    log(`Page failed to load: ${code} ${desc}`);
  });

  // Detect when renderer process truly freezes (not just slow network)
  mainWin.webContents.on('unresponsive', () => {
    log('Window became unresponsive');
    dialog.showMessageBox(mainWin, {
      type: 'warning',
      title: 'QPOS is not responding',
      message: 'The page stopped responding. Reload it?',
      buttons: ['Reload', 'Wait'],
      defaultId: 0,
    }).then(({ response }) => {
      if (response === 0 && mainWin && !mainWin.isDestroyed()) mainWin.webContents.reload();
    });
  });

  mainWin.webContents.on('responsive', () => { log('Window responsive again'); });

  // Minimize to tray instead of closing
  mainWin.on('close', (e) => {
    if (!isQuitting) {
      e.preventDefault();
      mainWin.hide();
      if (tray) tray.displayBalloon({
        title: 'QPOS is still running',
        content: 'QPOS is minimized to the system tray. Right-click the tray icon to quit.',
        noSound: true,
      });
    }
  });

  mainWin.on('closed', () => { mainWin = null; });

  // Open external links in default browser
  mainWin.webContents.setWindowOpenHandler(({ url }) => {
    if (!url.startsWith(APP_URL)) shell.openExternal(url);
    return { action: 'deny' };
  });
}

// ── System Tray ──────────────────────────────────────────────────────────────
function createTray() {
  const icon = nativeImage.createFromPath(path.join(ASSETS, 'icon.png'));
  tray = new Tray(icon);
  tray.setToolTip('QPOS — Alkyne Solutions');

  const menu = Menu.buildFromTemplate([
    { label: 'Open QPOS', click: () => { if (mainWin) { mainWin.show(); mainWin.focus(); } } },
    { label: 'Reload Page  (F5)', click: () => { if (mainWin) { mainWin.show(); mainWin.webContents.reload(); } } },
    { label: 'Account Recovery', click: () => { shell.openExternal(`${APP_URL}/recovery`); } },
    { type: 'separator' },
    { label: 'Quit QPOS', click: () => {
        isQuitting = true;
        stopAll();
        app.quit();
      }
    },
  ]);

  tray.setContextMenu(menu);
  tray.on('double-click', () => { if (mainWin) { mainWin.show(); mainWin.focus(); } });
}

// ── Bootstrap ─────────────────────────────────────────────────────────────────
app.whenReady().then(async () => {
  createSplash();
  createTray();

  try {
    fs.mkdirSync(USER_DATA, { recursive: true });

    sendStatus('Writing database configuration...');
    writeMyIni();

    sendStatus('Checking database installation...');
    const freshInstall = await initDB();

    sendStatus('Starting database server...');
    await startDB();

    sendStatus('Configuring database connection...');
    await fixAuthPlugin();

    if (freshInstall) {
      sendStatus('First-time setup: creating database tables...');
      await firstTimeSetup();
      sendStatus('Setting up environment...');
    } else {
      // Ensure .env exists
      if (fs.existsSync(ENV_PROD)) {
        fs.copyFileSync(ENV_PROD, ENV_FILE); // always overwrite to ensure correct production settings
      }
    }

    sendStatus('Preparing storage directories...');
    ensureStorageDirs();

    sendStatus('Starting web server...');
    await startPHP();

    sendStatus('Opening QPOS...');
    createMain();

    // Keyboard shortcuts
    globalShortcut.register('F5', () => { if (mainWin) mainWin.webContents.reload(); });
    globalShortcut.register('CommandOrControl+R', () => { if (mainWin) mainWin.webContents.reload(); });
    globalShortcut.register('Alt+Left',  () => { if (mainWin && mainWin.webContents.canGoBack())    mainWin.webContents.goBack(); });
    globalShortcut.register('Alt+Right', () => { if (mainWin && mainWin.webContents.canGoForward()) mainWin.webContents.goForward(); });

  } catch (err) {
    log('STARTUP ERROR: ' + err.message);
    if (splashWin && !splashWin.isDestroyed()) {
      splashWin.webContents.send('error', err.message);
    }
  }
});

// ── Cleanup on quit ──────────────────────────────────────────────────────────
app.on('before-quit', () => { isQuitting = true; globalShortcut.unregisterAll(); stopAll(); });
app.on('window-all-closed', () => {}); // Keep running in tray
