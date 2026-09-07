@echo off
echo ========================================
echo Fixing User Passwords
echo UniSIRAJ Automated Attendance System
echo ========================================
echo.
echo This will update all passwords to: Admin@123
echo.
pause

echo.
echo Running SQL fix...
mysql -u root -p unisiraj_attendance < fix-passwords.sql

echo.
echo ========================================
echo Password fix complete!
echo ========================================
echo.
echo You can now login with:
echo Email: admin@unisiraj.edu.my
echo Password: Admin@123
echo.
pause
