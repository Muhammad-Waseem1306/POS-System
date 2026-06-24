@echo off
title QPOS - First Time Setup
color 0A

SET INSTALL_DIR=C:\QPOS
SET PHP=%INSTALL_DIR%\php\php.exe
SET MYSQL_BIN=%INSTALL_DIR%\mariadb\bin
SET APP_DIR=%INSTALL_DIR%\app
SET DATA_DIR=%INSTALL_DIR%\mariadb\data

echo.
echo  ============================================
echo   QPOS - Muhammad_Waseem Electronics
echo   First Time Setup
echo  ============================================
echo.

REM ---- Step 1: Write my.ini ----
echo [1/6] Writing database configuration...
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
echo     Configuration written.

REM ---- Step 2: Initialize DB ----
echo.
echo [2/6] Initializing MariaDB database engine...
if exist "%DATA_DIR%\mysql" (
    echo     Already initialized - skipping.
) else (
    REM Clear any partial data
    del /Q /F "%DATA_DIR%\*.*" >nul 2>&1
    for /D %%i in ("%DATA_DIR%\*") do rd /S /Q "%%i" >nul 2>&1

    "%MYSQL_BIN%\mariadb-install-db.exe" --datadir="%DATA_DIR%" --password=qpos_secret
    if %ERRORLEVEL% NEQ 0 (
        echo     ERROR: MariaDB initialization failed!
        pause
        exit /b 1
    )
    echo     Database engine initialized.
)

REM ---- Step 3: Start DB ----
echo.
echo [3/6] Starting database server...
start /b "" "%MYSQL_BIN%\mysqld.exe" --defaults-file="%INSTALL_DIR%\mariadb\my.ini"
echo     Waiting 8 seconds for database to be ready...
timeout /t 8 /nobreak >nul

REM ---- Step 4: Create database ----
echo.
echo [4/6] Creating application database...
"%MYSQL_BIN%\mysql.exe" -u root --password=qpos_secret --connect-timeout=10 -e "CREATE DATABASE IF NOT EXISTS electronics_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
echo     Database 'electronics_store' ready.

REM ---- Step 5: Setup .env ----
echo.
echo [5/6] Configuring application environment...
if not exist "%APP_DIR%\.env" (
    copy "%APP_DIR%\.env.production" "%APP_DIR%\.env" >nul
    echo     Environment file created.
) else (
    echo     Environment file already exists.
)

REM ---- Step 6: Migrate, seed, optimize ----
echo.
echo [6/6] Setting up database tables and initial data...
"%PHP%" "%APP_DIR%\artisan" migrate --force
"%PHP%" "%APP_DIR%\artisan" db:seed --force
"%PHP%" "%APP_DIR%\artisan" storage:link
"%PHP%" "%APP_DIR%\artisan" optimize:clear

REM ---- Stop DB ----
echo.
echo     Stopping setup services...
"%MYSQL_BIN%\mysqladmin.exe" -u root --password=qpos_secret shutdown >nul 2>&1
timeout /t 4 /nobreak >nul
taskkill /F /IM mysqld.exe >nul 2>&1

echo.
echo  ============================================
echo   Setup Complete!
echo.
echo   Now double-click "Start QPOS" on your
echo   Desktop to launch the application.
echo.
echo   First time: Register an account
echo   (first registered user = Admin)
echo  ============================================
echo.
pause
