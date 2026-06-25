(function (window) {
    'use strict';

    var viteLoadPromise = null;

    function waitForMountReactApps(timeoutMs) {
        return new Promise(function (resolve, reject) {
            if (typeof window.mountReactApps === 'function') {
                resolve();
                return;
            }

            var elapsed = 0;
            var step = 50;
            var timer = window.setInterval(function () {
                if (typeof window.mountReactApps === 'function') {
                    window.clearInterval(timer);
                    resolve();
                    return;
                }

                elapsed += step;
                if (elapsed >= timeoutMs) {
                    window.clearInterval(timer);
                    reject(new Error('Timed out waiting for mountReactApps'));
                }
            }, step);
        });
    }

    function findModuleScript(src) {
        if (!src) {
            return null;
        }

        var scripts = document.querySelectorAll('script[type="module"][src]');
        for (var i = 0; i < scripts.length; i++) {
            if (scripts[i].src === src || scripts[i].getAttribute('src') === src) {
                return scripts[i];
            }
        }

        return null;
    }

    function injectModuleScript(src) {
        return new Promise(function (resolve, reject) {
            var script = document.createElement('script');
            script.type = 'module';
            script.src = src;
            script.onload = function () {
                resolve();
            };
            script.onerror = function () {
                reject(new Error('Script failed: ' + src));
            };
            document.body.appendChild(script);
        });
    }

    function loadModuleScript(src) {
        if (!src) {
            return Promise.reject(new Error('Missing script URL'));
        }

        if (typeof window.mountReactApps === 'function') {
            return Promise.resolve();
        }

        var chain = Promise.resolve();

        if (!findModuleScript(src)) {
            chain = injectModuleScript(src);
        }

        return chain.then(function () {
            return waitForMountReactApps(15000);
        });
    }

    function ensureViteEntry() {
        if (typeof window.mountReactApps === 'function') {
            return Promise.resolve();
        }

        if (viteLoadPromise) {
            return viteLoadPromise;
        }

        var entry = window.__vitePosEntry;
        var build = window.__vitePosBuild;

        viteLoadPromise = Promise.race([
            loadModuleScript(entry),
            new Promise(function (resolve, reject) {
                window.setTimeout(function () {
                    if (typeof window.mountReactApps === 'function') {
                        resolve();
                        return;
                    }

                    reject(new Error('Dev entry slow'));
                }, 2000);
            }),
        ]).catch(function () {
            if (build && build !== entry) {
                return loadModuleScript(build);
            }

            return loadModuleScript(entry);
        }).finally(function () {
            viteLoadPromise = null;
        });

        return viteLoadPromise;
    }

    function showBootError(rootId, message) {
        var root = document.getElementById(rootId);
        if (!root || !root.querySelector('.pos-shell__loading')) {
            return;
        }

        root.innerHTML =
            '<div class="content-card p-4 text-center text-danger pos-shell__loading">' +
            '<i class="fas fa-exclamation-triangle fa-2x mb-2" aria-hidden="true"></i>' +
            '<p class="mb-2">' + message + '</p>' +
            '<button type="button" class="btn btn-modern btn-modern--primary" onclick="location.reload()">Reload page</button>' +
            '</div>';
    }

    function bootPosApp(rootId, attempt) {
        attempt = attempt || 0;

        ensureViteEntry().then(function () {
            if (typeof window.mountReactApps === 'function') {
                window.mountReactApps();
            }

            var root = document.getElementById(rootId);
            if (!root) {
                return;
            }

            if (root.querySelector('.pos-shell__loading') && attempt < 120) {
                window.setTimeout(function () {
                    bootPosApp(rootId, attempt + 1);
                }, 50);
                return;
            }

            if (root.querySelector('.pos-shell__loading')) {
                showBootError(rootId, 'Unable to load POS application scripts.');
            }
        }).catch(function () {
            if (attempt < 5) {
                window.setTimeout(function () {
                    bootPosApp(rootId, attempt + 1);
                }, 200);
                return;
            }

            showBootError(rootId, 'Unable to load POS application scripts. Try a hard refresh or run npm run build.');
        });
    }

    window.bootPosApp = bootPosApp;
    window.ensurePosViteEntry = ensureViteEntry;

    function preloadPosVite() {
        ensureViteEntry().catch(function () {
            // Ignore preload errors; bootPosApp will retry when needed.
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', preloadPosVite);
    } else {
        preloadPosVite();
    }

    document.addEventListener('app:page-loaded', function () {
        if (document.getElementById('cart')) {
            bootPosApp('cart');
        }
        if (document.getElementById('purchase')) {
            bootPosApp('purchase');
        }
    });
}(window));
