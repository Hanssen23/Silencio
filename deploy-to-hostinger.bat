@echo off
REM Automated Deployment Script for Silencio Gym Management System to Hostinger VPS
REM Windows Batch version for easy execution

setlocal enabledelayedexpansion

REM Configuration
set VPS_IP=156.67.221.184
set VPS_USER=root
set APP_DIR=/var/www/silencio-gym
set DOMAIN=yourdomain.com
set GITHUB_TOKEN=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
set REPO_URL=https://github.com/Hanssen23/Silencio.git

echo.
echo ========================================
echo  Silencio Gym Management System
echo  Hostinger VPS Deployment Script
echo ========================================
echo.
echo VPS IP: %VPS_IP%
echo Target Directory: %APP_DIR%
echo.

REM Check if SSH is available
where ssh >nul 2>nul
if %errorlevel% neq 0 (
    echo ERROR: SSH client not found. Please install OpenSSH or use PuTTY.
    echo Download from: https://www.putty.org/
    pause
    exit /b 1
)

echo Step 1: Preparing VPS environment...
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "apt update && apt upgrade -y && apt install -y nginx php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip php8.2-bcmath php8.2-gd php8.2-sqlite3 composer nodejs npm git unzip mysql-server"

echo Step 2: Setting up web server...
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "cat > /etc/nginx/sites-available/silencio-gym << 'EOF'
server {
    listen 80;
    server_name %DOMAIN% www.%DOMAIN%;
    root %APP_DIR%/public;
    index index.php index.html;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF"

ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "ln -sf /etc/nginx/sites-available/silencio-gym /etc/nginx/sites-enabled/ && rm -f /etc/nginx/sites-enabled/default && nginx -t && systemctl reload nginx"

echo Step 3: Setting up MySQL database...
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "mysql -e \"CREATE DATABASE IF NOT EXISTS silencio_gym_db;\" && mysql -e \"CREATE USER IF NOT EXISTS 'silencio_user'@'localhost' IDENTIFIED BY 'SilencioGym2024!';\" && mysql -e \"GRANT ALL PRIVILEGES ON silencio_gym_db.* TO 'silencio_user'@'localhost';\" && mysql -e \"FLUSH PRIVILEGES;\""

echo Step 4: Deploying application...
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "mkdir -p %APP_DIR%"
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "cd %APP_DIR% && if [ -d '.git' ]; then git pull origin main; else git clone %REPO_URL% .; fi"

echo Step 5: Installing dependencies...
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "cd %APP_DIR% && composer install --optimize-autoloader --no-dev"
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "cd %APP_DIR% && npm install && npm run build"

echo Step 6: Configuring environment...
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "cd %APP_DIR% && cat > .env << 'EOF'
APP_NAME=\"Silencio Gym Management System\"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://%DOMAIN%

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=silencio_gym_db
DB_USERNAME=silencio_user
DB_PASSWORD=SilencioGym2024!

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

CACHE_DRIVER=database
CACHE_PREFIX=

QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=noreply@%DOMAIN%
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@%DOMAIN%
MAIL_FROM_NAME=\"\${APP_NAME}\"

RFID_DEVICE_ID=main_reader
RFID_API_URL=https://%DOMAIN%

BCRYPT_ROUNDS=12
EOF"

echo Step 7: Setting up Laravel application...
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "cd %APP_DIR% && php artisan key:generate --force && php artisan migrate --force && php artisan db:seed --force && php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan storage:link"

echo Step 8: Setting file permissions...
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "cd %APP_DIR% && chown -R www-data:www-data . && chmod -R 755 storage/ && chmod -R 755 bootstrap/cache/ && chmod -R 755 public/ && chmod 600 .env"

echo Step 9: Setting up SSL certificate...
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "apt install -y certbot python3-certbot-nginx && certbot --nginx -d %DOMAIN% -d www.%DOMAIN% --non-interactive --agree-tos --email admin@%DOMAIN%"

echo Step 10: Setting up RFID system...
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "apt install -y python3 python3-pip && pip3 install pyscard requests"

echo Step 11: Creating RFID service...
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "cat > /etc/systemd/system/silencio-rfid.service << 'EOF'
[Unit]
Description=Silencio Gym RFID Reader Service
After=network.target

[Service]
Type=simple
User=www-data
WorkingDirectory=%APP_DIR%
ExecStart=/usr/bin/python3 %APP_DIR%/rfid_reader.py --api https://%DOMAIN%
Restart=always
RestartSec=10

[Install]
WantedBy=multi-user.target
EOF"

ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "systemctl daemon-reload && systemctl enable silencio-rfid.service"

echo Step 12: Final optimizations...
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "cd %APP_DIR% && composer dump-autoload --optimize && php artisan optimize"

echo.
echo ========================================
echo  DEPLOYMENT COMPLETED SUCCESSFULLY!
echo ========================================
echo.
echo Application URL: https://%DOMAIN%
echo Admin Login: admin@admin.com / admin123
echo Database: silencio_gym_db
echo Database User: silencio_user
echo Application Directory: %APP_DIR%
echo.
echo Next Steps:
echo 1. Update your domain DNS to point to %VPS_IP%
echo 2. Test your application at https://%DOMAIN%
echo 3. Configure email settings in .env file
echo 4. Start RFID service: systemctl start silencio-rfid
echo.
echo Useful Commands:
echo - Check status: systemctl status nginx php8.2-fpm
echo - View logs: tail -f %APP_DIR%/storage/logs/laravel.log
echo - Restart services: systemctl restart nginx php8.2-fpm silencio-rfid
echo.
echo Your Silencio Gym Management System is now live!
echo.
pause
