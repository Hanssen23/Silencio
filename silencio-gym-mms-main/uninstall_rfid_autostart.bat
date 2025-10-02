@echo off
echo Uninstalling RFID System Auto-Start...

REM Remove from Windows startup folder
set "startup_folder=%APPDATA%\Microsoft\Windows\Start Menu\Programs\Startup"
del "%startup_folder%\RFID_System_Startup.vbs" 2>nul

REM Remove VBS script
del "start_rfid_hidden.vbs" 2>nul

echo RFID System auto-start uninstalled successfully!
echo The system will no longer start automatically.
echo.
pause
