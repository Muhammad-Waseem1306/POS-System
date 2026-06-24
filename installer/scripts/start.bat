@echo off
title QPOS - Starting...
color 0A

SET INSTALL_DIR=C:\QPOS
SET PHP=%INSTALL_DIR%\php\php.exe
SET MYSQL_BIN=%INSTALL_DIR%\mariadb\bin
SET DATA_DIR=%INSTALL_DIR%\mariadb\data
SET APP_DIR=%INSTALL_DIR%\app

echo.
echo  ============================================
echo   QPOS - Muhammad_Waseem Electronics
echo  ============================================
echo.

REM ---- Ensure my.ini exists ----
if not exist "%INSTALL_DIR%\mariadb\my.ini" (
    (
    echo [mysqld]
    echo datadir=%DATA_DIR%
    echo port=3306
    echo innodb_buffer_pool_size=64M
    echo max_connections=20
    echo character-set-server=utf8mb4
    echo collation-server=utf8mb4_unicode_ci
    echo [client]
    echo port=3306
    ) > "%INSTALL_DIR%\mariadb\my.ini"
)

REM ---- Check if DB already running ----
"%MYSQL_BIN%\mysqladmin.exe" -u root --password=qpos_secret status >nul 2>&1
if %ERRORLEVEL%==0 (
    echo   [DB] Database server already running.
) else (
    echo   [DB] Starting database server...
    start /b "" "%MYSQL_BIN%\mysqld.exe" --datadir="%DATA_DIR%" --port=3306
    timeout /t 5 /nobreak >nul
    echo   [DB] Database server started.
)

REM ---- Check if web server already running ----
netstat -ano | findstr ":8000" >nul 2>&1
if %ERRORLEVEL%==0 (
    echo   [WEB] Web server already running on port 8000.
) else (
    echo   [WEB] Starting web server on port 8000...
    start /min "QPOS-WEB" "%PHP%" -S 127.0.0.1:8000 -t "%APP_DIR%\public" "%APP_DIR%\server.php"
    timeout /t 2 /nobreak >nul
    echo   [WEB] Web server started.
)

echo.
echo   Opening QPOS in your browser...
timeout /t 1 /nobreak >nul
start "" "http://127.0.0.1:8000"

echo.
echo  ============================================
echo   QPOS is running at: http://127.0.0.1:8000
echo.
echo   Keep this window open while using QPOS.
echo   Use the "Stop QPOS" shortcut to shut down.
echo  ============================================
echo.
pause
