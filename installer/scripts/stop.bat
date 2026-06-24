@echo off
title QPOS - Stopping...
color 0C

SET INSTALL_DIR=C:\QPOS
SET MYSQL_BIN=%INSTALL_DIR%\mariadb\bin

echo.
echo  ============================================
echo   QPOS - Stopping Application...
echo  ============================================
echo.

echo   Stopping web server (PHP)...
taskkill /F /IM php.exe >nul 2>&1
echo   Web server stopped.

echo.
echo   Stopping database server (MariaDB)...
"%MYSQL_BIN%\mysqladmin.exe" -u root --password=qpos_secret shutdown >nul 2>&1
timeout /t 3 /nobreak >nul
taskkill /F /IM mysqld.exe >nul 2>&1
echo   Database server stopped.

echo.
echo  ============================================
echo   QPOS stopped safely.
echo  ============================================
echo.
timeout /t 2 /nobreak >nul
