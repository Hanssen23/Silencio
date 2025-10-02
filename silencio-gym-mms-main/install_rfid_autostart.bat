@echo off
echo Installing RFID System Auto-Start...

REM Create a VBS script to run the batch file hidden
echo Set WshShell = CreateObject("WScript.Shell") > start_rfid_hidden.vbs
echo WshShell.Run "start_rfid_auto.bat", 0, False >> start_rfid_hidden.vbs

REM Add to Windows startup folder
set "startup_folder=%APPDATA%\Microsoft\Windows\Start Menu\Programs\Startup"
copy "start_rfid_hidden.vbs" "%startup_folder%\RFID_System_Startup.vbs"

echo RFID System auto-start installed successfully!
echo The system will now start automatically when you log in.
echo.
echo To uninstall, delete: %startup_folder%\RFID_System_Startup.vbs
echo.
pause
