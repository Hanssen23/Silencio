# 🚀 Silencio Gym Management System - Hostinger VPS Deployment Guide

This guide will help you deploy your Laravel-based gym management system to your Hostinger VPS using the automated deployment scripts.

## 📋 Prerequisites

### What You Need
- ✅ Hostinger VPS with Ubuntu 22.04 LTS (already set up)
- ✅ SSH access to your VPS (IP: 156.67.221.184)
- ✅ GitHub Personal Access Token (provided)
- ✅ Domain name (optional but recommended)
- ✅ SSH client installed on your local machine

### VPS Details
- **IP Address**: 156.67.221.184
- **Username**: root
- **Operating System**: Ubuntu 22.04 LTS
- **Status**: Running ✅

## 🛠️ Quick Deployment (Recommended)

### Option 1: PowerShell Script (Windows)
```powershell
# Run as Administrator
.\deploy-to-hostinger.ps1 -DOMAIN "yourdomain.com"
```

### Option 2: Batch File (Windows)
```cmd
# Double-click or run in Command Prompt
deploy-to-hostinger.bat
```

### Option 3: Bash Script (Linux/Mac)
```bash
# Make executable and run
chmod +x deploy-to-hostinger.sh
./deploy-to-hostinger.sh
```

## ⚙️ Manual Configuration

### Step 1: Update Configuration
1. Open `deployment-config.conf`
2. Replace `yourdomain.com` with your actual domain
3. Update database password if needed
4. Configure email settings

### Step 2: Run Deployment
Choose your preferred method and execute the deployment script.

## 📊 What the Script Does

### 1. **System Setup**
- Updates Ubuntu packages
- Installs Nginx, PHP 8.2, MySQL, Node.js, Composer
- Configures web server

### 2. **Database Setup**
- Creates MySQL database: `silencio_gym_db`
- Creates database user: `silencio_user`
- Sets up proper permissions

### 3. **Application Deployment**
- Clones your GitHub repository
- Installs PHP dependencies (Composer)
- Installs Node.js dependencies
- Builds frontend assets

### 4. **Laravel Configuration**
- Creates production `.env` file
- Generates application key
- Runs database migrations
- Seeds initial data
- Optimizes application

### 5. **Security Setup**
- Configures SSL certificate (Let's Encrypt)
- Sets proper file permissions
- Secures sensitive files

### 6. **RFID System Setup**
- Installs Python dependencies
- Creates systemd service
- Configures RFID reader integration

## 🔧 Post-Deployment Steps

### 1. Domain Configuration
If you have a domain:
1. Update DNS A record to point to `156.67.221.184`
2. Wait for DNS propagation (up to 24 hours)
3. Test your site at `https://yourdomain.com`

### 2. Test Your Application
- **URL**: `https://yourdomain.com` or `http://156.67.221.184`
- **Admin Login**: 
  - Email: `admin@admin.com`
  - Password: `admin123`

### 3. Configure Email (Optional)
Update `.env` file on the server:
```bash
ssh root@156.67.221.184
cd /var/www/silencio-gym
nano .env
```

Update these lines:
```env
MAIL_USERNAME=your_email@yourdomain.com
MAIL_PASSWORD=your_email_password
```

### 4. Start RFID Service (Optional)
```bash
ssh root@156.67.221.184
systemctl start silencio-rfid
systemctl status silencio-rfid
```

## 🔍 Troubleshooting

### Common Issues

#### 1. **SSH Connection Failed**
- Ensure SSH client is installed
- Check if VPS is running
- Verify IP address: 156.67.221.184

#### 2. **Domain Not Working**
- Check DNS settings
- Wait for DNS propagation
- Use IP address temporarily: `http://156.67.221.184`

#### 3. **Database Connection Error**
- Check MySQL service: `systemctl status mysql`
- Verify database credentials in `.env`
- Restart MySQL: `systemctl restart mysql`

#### 4. **Application Not Loading**
- Check Nginx status: `systemctl status nginx`
- Check PHP-FPM status: `systemctl status php8.2-fpm`
- View error logs: `tail -f /var/www/silencio-gym/storage/logs/laravel.log`

#### 5. **SSL Certificate Issues**
- Check Certbot status: `certbot certificates`
- Renew certificate: `certbot renew --dry-run`

### Useful Commands

```bash
# Connect to VPS
ssh root@156.67.221.184

# Check application status
systemctl status nginx php8.2-fpm mysql

# View application logs
tail -f /var/www/silencio-gym/storage/logs/laravel.log

# Restart services
systemctl restart nginx php8.2-fpm mysql

# Update application
cd /var/www/silencio-gym
git pull origin main
composer install --no-dev
php artisan migrate --force
php artisan config:cache
```

## 📈 Performance Optimization

### 1. **Enable Caching**
```bash
cd /var/www/silencio-gym
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 2. **Optimize Database**
```bash
php artisan optimize
composer dump-autoload --optimize
```

### 3. **Monitor Resources**
```bash
# Check disk usage
df -h

# Check memory usage
free -h

# Check CPU usage
top
```

## 🔒 Security Checklist

- ✅ SSL certificate installed
- ✅ File permissions set correctly
- ✅ Database password secured
- ✅ Application key generated
- ✅ Debug mode disabled
- ✅ Error logging configured

## 📊 Monitoring

### Application Logs
- **Laravel Logs**: `/var/www/silencio-gym/storage/logs/laravel.log`
- **Nginx Logs**: `/var/log/nginx/access.log` and `/var/log/nginx/error.log`
- **System Logs**: `journalctl -u silencio-rfid -f`

### Health Checks
```bash
# Check if all services are running
systemctl status nginx php8.2-fpm mysql silencio-rfid

# Test application response
curl -I https://yourdomain.com

# Check database connection
mysql -u silencio_user -p silencio_gym_db -e "SELECT COUNT(*) FROM members;"
```

## 🔄 Updates and Maintenance

### Regular Updates
```bash
# Update system packages
apt update && apt upgrade -y

# Update application
cd /var/www/silencio-gym
git pull origin main
composer install --no-dev
php artisan migrate --force
php artisan config:cache
```

### Backup Strategy
```bash
# Backup database
mysqldump -u silencio_user -p silencio_gym_db > backup_$(date +%Y%m%d).sql

# Backup application files
tar -czf app_backup_$(date +%Y%m%d).tar.gz /var/www/silencio-gym
```

## 📞 Support

### Hostinger Support
- **Live Chat**: Available 24/7 in Hostinger control panel
- **Knowledge Base**: Comprehensive guides and tutorials
- **Community Forum**: User discussions and solutions

### Application Support
- Check error logs first
- Review this deployment guide
- Contact development team for application-specific issues

## 🎯 Success Checklist

- [ ] VPS is running and accessible
- [ ] SSH connection established
- [ ] Deployment script executed successfully
- [ ] Application loads without errors
- [ ] Admin login works
- [ ] Database is accessible
- [ ] SSL certificate is active
- [ ] RFID service is running (if needed)
- [ ] Email notifications work (if configured)
- [ ] Performance is acceptable

## 📝 Notes

- **Default Admin Credentials**: admin@admin.com / admin123
- **Database Name**: silencio_gym_db
- **Database User**: silencio_user
- **Application Directory**: /var/www/silencio-gym
- **Log Directory**: /var/www/silencio-gym/storage/logs/

---

**🎉 Congratulations!** Your Silencio Gym Management System should now be successfully deployed and running on your Hostinger VPS. The system includes:

- ✅ Complete gym management functionality
- ✅ RFID card integration
- ✅ Member management system
- ✅ Attendance tracking
- ✅ Payment processing
- ✅ Real-time monitoring
- ✅ Secure HTTPS access
- ✅ Automated backups
- ✅ Performance optimization

If you encounter any issues, refer to the troubleshooting section or contact support.
