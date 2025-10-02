@echo off
echo Uninstalling RFID System Windows Services...

REM Stop and remove services
echo Stopping services...
nssm stop "RFID_PHP_Server" 2>nul
nssm stop "RFID_Reader" 2>nul

echo Removing services...
nssm remove "RFID_PHP_Server" confirm 2>nul
nssm remove "RFID_Reader" confirm 2>nul

echo RFID System services uninstalled successfully!
echo.
pause
