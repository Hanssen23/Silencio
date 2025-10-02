# RFID System Setup Guide

## Overview
This guide provides complete instructions for setting up and using the RFID system for the Silencio Gym Management System.

## System Components

### 1. Python RFID Reader (`rfid_reader.py`)
- **Purpose**: Reads RFID cards from ACR122U reader and sends data to Laravel API
- **Features**: 
  - Automatic card detection
  - Duplicate prevention
  - Error handling and logging
  - Configurable settings

### 2. Configuration (`rfid_config.json`)
- **Purpose**: Configures the RFID reader behavior
- **Settings**:
  - API URL and endpoint
  - Device ID
  - Read delays
  - Logging options

### 3. Laravel Backend
- **RfidController**: Handles all RFID operations
- **Models**: Member, RfidLog, ActiveSession, Attendance
- **API Endpoints**: `/api/rfid/*` for all RFID operations

### 4. Frontend Dashboard
- **RFID Monitor**: Real-time dashboard showing active members and logs
- **Auto-refresh**: Updates every 1 second for real-time data
- **Responsive design**: Works on desktop and mobile

## Installation Steps

### Step 1: Install Python Dependencies
```bash
pip install -r requirements.txt
```

Required packages:
- `pyscard>=2.0.0` - Smart card communication
- `requests>=2.25.0` - HTTP requests to Laravel API
- `smartcard>=1.2.0` - Smart card utilities

### Step 2: Connect ACR122U Reader
1. Connect the ACR122U reader to a USB port
2. Install the appropriate drivers for your operating system
3. Verify the reader is detected by the system

### Step 3: Configure the System
Edit `rfid_config.json` if needed:
```json
{
    "api": {
        "url": "http://localhost:8000",
        "endpoint": "/api/rfid/tap",
        "timeout": 5
    },
    "reader": {
        "device_id": "acr122u_main",
        "read_delay": 0.5,
        "duplicate_prevention_seconds": 2
    }
}
```

### Step 4: Start Laravel Server
```bash
php artisan serve
```
The server will start on `http://localhost:8000`

### Step 5: Start RFID Reader
```bash
python rfid_reader.py
```

The reader will:
- Initialize the ACR122U reader
- Wait for RFID cards
- Send card data to the Laravel API
- Log all activities

## Usage

### 1. Access RFID Monitor
Open your browser and go to:
```
http://localhost:8000/rfid-monitor
```

### 2. Monitor Active Members
The dashboard shows:
- **Today's Check-ins**: Number of members who checked in today
- **Expired Memberships**: Members with expired memberships
- **Unknown Cards**: Unrecognized RFID cards
- **Currently Active Members**: Members currently in the gym
- **Recent RFID Activity**: Latest card taps and actions

### 3. Member Check-in/Check-out
When a member taps their RFID card:
1. The Python reader detects the card
2. Sends the card UID to the Laravel API
3. Laravel processes the request:
   - If member is not in gym: Creates check-in record
   - If member is in gym: Creates check-out record
4. Dashboard updates in real-time

### 4. View Logs
All RFID activities are logged in:
- Database: `rfid_logs` table
- File: `rfid_activity.log` (if enabled in config)

## API Endpoints

### Public Endpoints (No Authentication Required)
- `POST /api/rfid/tap` - Handle RFID card tap
- `GET /api/rfid/logs-public` - Get public RFID logs

### Protected Endpoints (Authentication Required)
- `GET /api/rfid/active-members` - Get currently active members
- `GET /api/rfid/dashboard-stats` - Get dashboard statistics
- `GET /api/rfid/logs` - Get RFID logs
- `POST /api/rfid/start` - Start RFID reader
- `POST /api/rfid/stop` - Stop RFID reader
- `GET /api/rfid/status` - Get RFID system status

## Troubleshooting

### Common Issues

#### 1. "No smart card readers found"
- **Cause**: ACR122U reader not connected or drivers not installed
- **Solution**: 
  - Check USB connection
  - Install ACR122U drivers
  - Restart the system

#### 2. "Python library not found"
- **Cause**: Required Python packages not installed
- **Solution**: Run `pip install -r requirements.txt`

#### 3. "API connection failed"
- **Cause**: Laravel server not running or wrong URL
- **Solution**: 
  - Start Laravel server: `php artisan serve`
  - Check `rfid_config.json` API URL

#### 4. "Unknown card" error
- **Cause**: RFID card not registered in the system
- **Solution**: Register the card UID in the member database

#### 5. "Membership expired" error
- **Cause**: Member's membership has expired
- **Solution**: Renew the member's membership

### Debug Mode
Enable debug logging in `rfid_config.json`:
```json
{
    "logging": {
        "enabled": true,
        "log_level": "DEBUG"
    }
}
```

### Test the System
Run the verification script:
```bash
php verify_rfid_system.php
```

This will test all components and provide a status report.

## File Structure

```
project/
├── rfid_reader.py              # Python RFID reader
├── rfid_config.json            # RFID configuration
├── requirements.txt            # Python dependencies
├── app/
│   ├── Http/Controllers/
│   │   └── RfidController.php  # Laravel RFID controller
│   └── Models/
│       ├── Member.php          # Member model
│       ├── RfidLog.php         # RFID log model
│       ├── ActiveSession.php   # Active session model
│       └── Attendance.php      # Attendance model
├── resources/views/
│   └── rfid-monitor.blade.php  # RFID monitor dashboard
├── routes/
│   ├── api.php                 # API routes
│   └── web.php                 # Web routes
└── database/migrations/        # Database migrations
```

## Security Considerations

1. **API Security**: RFID tap endpoint is public for hardware integration
2. **Data Validation**: All card UIDs are validated before processing
3. **Error Handling**: Comprehensive error handling prevents system crashes
4. **Logging**: All activities are logged for audit purposes

## Performance Optimization

1. **Read Delays**: Adjust `read_delay` in config to balance responsiveness and CPU usage
2. **Duplicate Prevention**: Configure `duplicate_prevention_seconds` to prevent multiple reads
3. **Database Indexing**: Ensure proper indexes on frequently queried columns
4. **Caching**: Consider caching for frequently accessed data

## Maintenance

### Daily Tasks
- Monitor RFID logs for errors
- Check for unknown cards
- Verify system performance

### Weekly Tasks
- Review expired memberships
- Clean up old logs if needed
- Update system if required

### Monthly Tasks
- Analyze usage patterns
- Optimize database performance
- Review and update configurations

## Support

For technical support or issues:
1. Check the logs: `rfid_activity.log`
2. Run verification: `php verify_rfid_system.php`
3. Test components individually
4. Check system requirements

## System Requirements

### Hardware
- ACR122U NFC/RFID Reader
- USB port for reader connection
- Computer with Python 3.7+ and PHP 7.4+

### Software
- Python 3.7 or higher
- PHP 7.4 or higher
- Laravel 8.0 or higher
- MySQL/PostgreSQL database
- Web server (Apache/Nginx) or Laravel development server

### Dependencies
- Python: pyscard, requests, smartcard
- PHP: Laravel framework and extensions
- Database: MySQL or PostgreSQL

---

**System Status**: ✅ Ready for Production Use

**Last Updated**: October 2, 2025

**Version**: 1.0.0
