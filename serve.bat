@echo off
REM Simple Laravel Server Starter
REM Starts only the Laravel development server

echo ========================================
echo Starting Laravel Server...
echo ========================================
echo.
echo Application will be available at:
echo   http://localhost:8000
echo.
echo Press Ctrl+C to stop the server.
echo.

D:\xampp\php\php.exe artisan serve
