@echo off
echo Installing RFID System as Windows Service...

REM Check if NSSM is available
where nssm >nul 2>&1
if %errorlevel% neq 0 (
    echo NSSM (Non-Sucking Service Manager) is required but not found.
    echo Please download NSSM from: https://nssm.cc/download
    echo Extract nssm.exe to C:\Windows\System32\ or add to PATH
    echo.
    pause
    exit /b 1
)

REM Install PHP server service
echo Installing PHP Server service...
nssm install "RFID_PHP_Server" "C:\Users\hanss\Documents\silencio-gym-mms-main\silencio-gym-mms-main\php.exe" "-S localhost:8007 -t public"
nssm set "RFID_PHP_Server" AppDirectory "C:\Users\hanss\Documents\silencio-gym-mms-main\silencio-gym-mms-main"
nssm set "RFID_PHP_Server" DisplayName "RFID PHP Server"
nssm set "RFID_PHP_Server" Description "Laravel PHP server for RFID system"
nssm set "RFID_PHP_Server" Start SERVICE_AUTO_START

REM Install RFID reader service
echo Installing RFID Reader service...
nssm install "RFID_Reader" "C:\Users\hanss\Documents\silencio-gym-mms-main\silencio-gym-mms-main\python.exe" "rfid_reader.py"
nssm set "RFID_Reader" AppDirectory "C:\Users\hanss\Documents\silencio-gym-mms-main\silencio-gym-mms-main"
nssm set "RFID_Reader" DisplayName "RFID Reader"
nssm set "RFID_Reader" Description "ACR122U RFID card reader service"
nssm set "RFID_Reader" Start SERVICE_AUTO_START

REM Start the services
echo Starting services...
nssm start "RFID_PHP_Server"
nssm start "RFID_Reader"

echo RFID System installed as Windows services successfully!
echo Services will start automatically on system boot.
echo.
echo To manage services:
echo - Start: nssm start "RFID_PHP_Server" / "RFID_Reader"
echo - Stop: nssm stop "RFID_PHP_Server" / "RFID_Reader"
echo - Remove: nssm remove "RFID_PHP_Server" / "RFID_Reader" confirm
echo.
pause
