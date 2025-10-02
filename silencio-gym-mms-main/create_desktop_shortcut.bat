@echo off
echo Creating desktop shortcut for RFID System...

REM Create VBS script to create shortcut
echo Set oWS = WScript.CreateObject("WScript.Shell") > CreateShortcut.vbs
echo sLinkFile = "%USERPROFILE%\Desktop\RFID System.lnk" >> CreateShortcut.vbs
echo Set oLink = oWS.CreateShortcut(sLinkFile) >> CreateShortcut.vbs
echo oLink.TargetPath = "%~dp0start_rfid_simple.bat" >> CreateShortcut.vbs
echo oLink.WorkingDirectory = "%~dp0" >> CreateShortcut.vbs
echo oLink.Description = "Start RFID System" >> CreateShortcut.vbs
echo oLink.Save >> CreateShortcut.vbs

REM Run the VBS script
cscript CreateShortcut.vbs

REM Clean up
del CreateShortcut.vbs

echo Desktop shortcut created successfully!
echo You can now double-click "RFID System" on your desktop to start the system.
echo.
pause
