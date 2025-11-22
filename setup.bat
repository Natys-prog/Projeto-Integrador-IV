@echo off
REM =================================
REM Laravel Project Setup Script (Windows)
REM =================================

echo 🚀 Starting Laravel Project Setup...
echo ====================================

REM Check if we're in the right directory
if not exist "laravel-app\artisan" (
    echo [ERROR] Laravel artisan file not found. Please run this script from the project root directory.
    pause
    exit /b 1
)

echo [INFO] Navigating to Laravel directory...
cd laravel-app

REM Check PHP
php -v >nul 2>&1
if errorlevel 1 (
    echo [ERROR] PHP is not installed or not in PATH
    echo Please install PHP 8.1 or higher from https://www.php.net/downloads
    pause
    exit /b 1
) else (
    echo [SUCCESS] PHP found
)

REM Check Composer
composer --version >nul 2>&1
if errorlevel 1 (
    echo [WARNING] Composer not found
    echo Please install Composer from https://getcomposer.org/download/
    echo After installation, please run this script again
    pause
    exit /b 1
) else (
    echo [SUCCESS] Composer found
)

REM Check Node.js
node -v >nul 2>&1
if errorlevel 1 (
    echo [WARNING] Node.js not found
    echo Please install Node.js from https://nodejs.org/
    echo After installation, please run this script again
    pause
    exit /b 1
) else (
    echo [SUCCESS] Node.js found
)

REM Check npm
npm -v >nul 2>&1
if errorlevel 1 (
    echo [ERROR] npm not found. Please install npm
    pause
    exit /b 1
) else (
    echo [SUCCESS] npm found
)

echo [INFO] Installing PHP dependencies with Composer...
composer install --no-dev --optimize-autoloader

echo [INFO] Installing Node.js dependencies...
npm install

echo [INFO] Building frontend assets...
npm run build

REM Generate application key if not exists
findstr /C:"APP_KEY=base64:" .env >nul 2>&1
if errorlevel 1 (
    echo [INFO] Generating application key...
    php artisan key:generate
) else (
    echo [SUCCESS] Application key already exists
)

echo [INFO] Optimizing Laravel...
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

echo [SUCCESS] ✅ Setup completed successfully!
echo.
echo 🎉 Your Laravel application is ready to run!
echo.
echo To start the development server:
echo   cd laravel-app
echo   php artisan serve
echo.
echo To access your application:
echo   http://127.0.0.1:8000
echo.
echo Visit http://127.0.0.1:8000/init-db to initialize the database tables
echo.
pause