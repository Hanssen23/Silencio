@echo off
echo Starting RFID System...

REM Change to the project directory
cd /d "C:\Users\hanss\Documents\silencio-gym-mms-main\silencio-gym-mms-main"

REM Start PHP server in background
echo Starting PHP server...
start /B php -S localhost:8007 -t public

REM Wait a moment for server to start
timeout /t 3 /nobreak >nul

REM Start RFID reader in background
echo Starting RFID reader...
start /B python rfid_reader.py

echo RFID System started successfully!
echo PHP Server: http://localhost:8007
echo RFID Monitor: http://localhost:8007/rfid-monitor
echo.
echo Press any key to stop the system...
pause >nul

REM Stop the processes
echo Stopping RFID System...
taskkill /F /IM php.exe >nul 2>&1
taskkill /F /IM python.exe >nul 2>&1
echo RFID System stopped.
