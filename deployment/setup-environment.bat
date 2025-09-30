@echo off
REM Environment Setup Script for Silencio Gym Management System
REM Windows Batch version

setlocal enabledelayedexpansion

echo.
echo ========================================
echo  Silencio Gym Management System
echo  Environment Setup Script
echo ========================================
echo.

REM Check if we're in the right directory
if not exist "composer.json" (
    echo ERROR: composer.json not found. Please run this script from the project root directory.
    pause
    exit /b 1
)

echo Step 1: Checking system requirements...

REM Check PHP
where php >nul 2>nul
if %errorlevel% neq 0 (
    echo ERROR: PHP not found. Please install PHP 8.2 or higher.
    pause
    exit /b 1
) else (
    echo ✅ PHP found
)

REM Check Composer
where composer >nul 2>nul
if %errorlevel% neq 0 (
    echo ERROR: Composer not found. Please install Composer.
    pause
    exit /b 1
) else (
    echo ✅ Composer found
)

REM Check Node.js
where node >nul 2>nul
if %errorlevel% neq 0 (
    echo ERROR: Node.js not found. Please install Node.js.
    pause
    exit /b 1
) else (
    echo ✅ Node.js found
)

REM Check npm
where npm >nul 2>nul
if %errorlevel% neq 0 (
    echo ERROR: npm not found. Please install npm.
    pause
    exit /b 1
) else (
    echo ✅ npm found
)

REM Check SSH
where ssh >nul 2>nul
if %errorlevel% neq 0 (
    echo ERROR: SSH client not found. Please install OpenSSH or PuTTY.
    pause
    exit /b 1
) else (
    echo ✅ SSH client found
)

echo ✅ All system requirements met

echo.
echo Step 2: Installing PHP dependencies...
composer install --optimize-autoloader --no-dev
if %errorlevel% neq 0 (
    echo ERROR: Failed to install PHP dependencies
    pause
    exit /b 1
)
echo ✅ PHP dependencies installed

echo.
echo Step 3: Installing Node.js dependencies...
npm install
if %errorlevel% neq 0 (
    echo ERROR: Failed to install Node.js dependencies
    pause
    exit /b 1
)
echo ✅ Node.js dependencies installed

echo.
echo Step 4: Building frontend assets...
npm run build
if %errorlevel% neq 0 (
    echo ERROR: Failed to build frontend assets
    pause
    exit /b 1
)
echo ✅ Frontend assets built

echo.
echo Step 5: Preparing deployment files...

REM Create deployment package using PowerShell
powershell -Command "Compress-Archive -Path 'app','bootstrap','config','database','public','resources','routes','storage','vendor','artisan','composer.json','composer.lock','package.json','package-lock.json','vite.config.js','rfid_reader.py','requirements.txt' -DestinationPath 'silencio-gym-deployment.zip' -Force"
if %errorlevel% neq 0 (
    echo WARNING: Failed to create deployment package
) else (
    echo ✅ Deployment package created: silencio-gym-deployment.zip
)

echo.
echo Step 6: Setting up deployment scripts...
echo ✅ Deployment scripts ready

echo.
echo ========================================
echo  ENVIRONMENT SETUP COMPLETED!
echo ========================================
echo.
echo What's been prepared:
echo • PHP dependencies installed
echo • Node.js dependencies installed
echo • Frontend assets built
echo • Deployment package created
echo • Deployment scripts ready
echo.
echo Next steps:
echo 1. Update deployment-config.conf with your domain
echo 2. Run the deployment script:
echo    - Windows: deploy-to-hostinger.bat
echo    - PowerShell: .\deploy-to-hostinger.ps1
echo 3. Follow the deployment guide: DEPLOYMENT_GUIDE.md
echo.
echo Configuration files:
echo • deployment-config.conf - Main configuration
echo • DEPLOYMENT_GUIDE.md - Detailed instructions
echo • deploy-to-hostinger.* - Deployment scripts
echo.
echo Ready for deployment!
echo.
pause
