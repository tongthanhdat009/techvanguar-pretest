@echo off
REM Flashcard Learning Hub - Development Server Starter
REM Start all required services for development

echo ========================================
echo Flashcard Learning Hub
echo Development Server Starter
echo ========================================
echo.

REM Get the directory where this script is located
set SCRIPT_DIR=%~dp0
cd /d "%SCRIPT_DIR%"

REM Check if PHP is available
where php >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] PHP not found in PATH!
    echo Please add PHP to your system PATH or install XAMPP/WAMP.
    pause
    exit /b 1
)

REM Check if Composer dependencies are installed
if not exist "%SCRIPT_DIR%vendor\" (
    echo [INFO] Composer dependencies not found. Installing...
    composer install
    if %ERRORLEVEL% NEQ 0 (
        echo [ERROR] Failed to install Composer dependencies.
        pause
        exit /b 1
    )
)

REM Check if Node modules are installed
if not exist "%SCRIPT_DIR%node_modules\" (
    echo [INFO] Node modules not found. Installing...
    npm install
    if %ERRORLEVEL% NEQ 0 (
        echo [ERROR] Failed to install Node modules.
        pause
        exit /b 1
    )
)

REM Create logs directory if it doesn't exist
if not exist "%SCRIPT_DIR%storage\logs\" (
    mkdir "%SCRIPT_DIR%storage\logs"
)

echo.
echo ========================================
echo Starting Development Services...
echo ========================================
echo.

echo [1/3] Starting Laravel Server...
start "Laravel Server" cmd /k "cd /d "%SCRIPT_DIR%" && php artisan serve"

echo [2/3] Starting Vite Dev Server...
start "Vite Dev Server" cmd /k "cd /d "%SCRIPT_DIR%" && npm run dev"

echo [3/3] Starting Queue Worker...
start "Queue Worker" cmd /k "cd /d "%SCRIPT_DIR%" && php artisan queue:listen --tries=1"

echo.
echo ========================================
echo All services started successfully!
echo ========================================
echo.
echo Services running in separate windows:
echo   - Laravel Server:  http://localhost:8000
echo   - Vite Dev Server: http://localhost:5173
echo   - Queue Worker:    Processing background jobs
echo.
echo NOTE: Pail (Log Watcher) is not available on Windows.
echo       View logs manually: storage/logs/laravel.log
echo.
echo Close this window to keep services running.
echo Press any key to stop all services...
pause >nul

echo.
echo Stopping all services...
taskkill /FI "WINDOWTITLE eq Laravel Server*" /T /F >nul 2>nul
taskkill /FI "WINDOWTITLE eq Vite Dev Server*" /T /F >nul 2>nul
taskkill /FI "WINDOWTITLE eq Queue Worker*" /T /F >nul 2>nul
echo All services stopped.
