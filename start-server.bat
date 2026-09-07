@echo off
echo ==========================================
echo UniSIRAJ Attendance System
echo Starting Development Server...
echo ==========================================
echo.

cd /d "%~dp0"
php -S localhost:8000 -t public

echo.
echo Server stopped.
pause
