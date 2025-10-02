@echo off
title RFID System
echo ========================================
echo    RFID System Startup
echo ========================================
echo.

REM Change to project directory
cd /d "%~dp0"

REM Check if PHP server is already running
netstat -an | findstr :8007 >nul
if %errorlevel% equ 0 (
    echo PHP server is already running on port 8007
) else (
    echo Starting PHP server...
    start /B php -S localhost:8007 -t public
    timeout /t 2 /nobreak >nul
)

REM Check if RFID reader is already running
tasklist /FI "IMAGENAME eq python.exe" | findstr rfid_reader.py >nul
if %errorlevel% equ 0 (
    echo RFID reader is already running
) else (
    echo Starting RFID reader...
    start /B python rfid_reader.py
)

echo.
echo ========================================
echo RFID System is now running!
echo.
echo PHP Server: http://localhost:8007
echo RFID Monitor: http://localhost:8007/rfid-monitor
echo.
echo To stop the system, close this window or press Ctrl+C
echo ========================================
echo.

REM Keep the window open and show status
:loop
timeout /t 10 /nobreak >nul
echo [%date% %time%] RFID System is running...
goto loop
