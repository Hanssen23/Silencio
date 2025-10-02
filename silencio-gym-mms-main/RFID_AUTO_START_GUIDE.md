# RFID System Auto-Start Guide

This guide explains how to set up the RFID system to start automatically when you log in to Windows.

## Current Status
- ✅ RFID reader is working (detects cards)
- ✅ PHP server is working (API responds)
- ✅ Database integration is working
- ⚠️ System needs to be started manually each time

## Auto-Start Options

### Option 1: Simple Startup Script (Recommended)
**Best for: Personal use, easy management**

1. Run `create_desktop_shortcut.bat` to create a desktop shortcut
2. Double-click "RFID System" on your desktop to start
3. The system will run until you close the window

**Pros:**
- No admin rights required
- Easy to start/stop
- Visual feedback
- Can be run multiple times safely

**Cons:**
- Must be started manually
- Stops when window is closed

### Option 2: Windows Startup Folder
**Best for: Automatic startup on login**

1. Run `install_rfid_autostart.bat` as administrator
2. System will start automatically when you log in
3. Run `uninstall_rfid_autostart.bat` to remove

**Pros:**
- Starts automatically on login
- Runs in background
- No user interaction needed

**Cons:**
- Requires admin rights to install
- Harder to stop/restart
- May conflict with other instances

### Option 3: Windows Service (Advanced)
**Best for: Production use, always running**

1. Download NSSM from https://nssm.cc/download
2. Extract `nssm.exe` to `C:\Windows\System32\`
3. Run `install_rfid_service.bat` as administrator
4. System will start automatically on boot
5. Run `uninstall_rfid_service.bat` to remove

**Pros:**
- Starts automatically on boot
- Runs as Windows service
- Professional deployment
- Can be managed via Services console

**Cons:**
- Requires admin rights
- More complex setup
- Harder to debug

## Quick Start (Recommended)

1. **Create desktop shortcut:**
   ```cmd
   create_desktop_shortcut.bat
   ```

2. **Start the system:**
   - Double-click "RFID System" on desktop
   - Or run `start_rfid_simple.bat`

3. **Access the system:**
   - RFID Monitor: http://localhost:8007/rfid-monitor
   - Admin Panel: http://localhost:8007

## Troubleshooting

### System Not Starting
- Check if port 8007 is available: `netstat -an | findstr :8007`
- Kill existing processes: `taskkill /F /IM php.exe` and `taskkill /F /IM python.exe`
- Restart the system

### Card Not Detected
- Place the RFID card on the ACR122U reader
- Check `rfid_activity.log` for errors
- Ensure the card is properly positioned

### API Not Responding
- Verify PHP server is running: `tasklist /FI "IMAGENAME eq php.exe"`
- Test API directly: `test_laravel_api.php`
- Check firewall settings

## File Descriptions

- `start_rfid_simple.bat` - Simple startup script with status display
- `start_rfid_auto.bat` - Background startup script
- `install_rfid_autostart.bat` - Install auto-start in Windows startup folder
- `uninstall_rfid_autostart.bat` - Remove auto-start from Windows startup folder
- `install_rfid_service.bat` - Install as Windows service (requires NSSM)
- `uninstall_rfid_service.bat` - Remove Windows service
- `create_desktop_shortcut.bat` - Create desktop shortcut

## Current System Status

The RFID system is currently working correctly:
- PHP server running on port 8007
- RFID reader detecting cards
- API responding with check-in/check-out messages
- Database recording attendance and sessions

The only remaining step is to set up auto-start according to your preference.
