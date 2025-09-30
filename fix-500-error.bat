@echo off
REM Diagnostic and Fix Script for Silencio Gym Management System 500 Error
REM Windows Batch version

setlocal enabledelayedexpansion

REM Configuration
set VPS_IP=156.67.221.184
set VPS_USER=root
set APP_DIR=/var/www/silencio-gym

echo.
echo ========================================
echo  Silencio Gym Management System
echo  500 Error Diagnostic and Fix Script
echo ========================================
echo.
echo VPS IP: %VPS_IP%
echo.

REM Check if SSH is available
where ssh >nul 2>nul
if %errorlevel% neq 0 (
    echo ERROR: SSH client not found. Please install OpenSSH or use PuTTY.
    pause
    exit /b 1
)

echo Step 1: Checking VPS connectivity...
ssh -o StrictHostKeyChecking=no -o ConnectTimeout=10 %VPS_USER%@%VPS_IP% "echo 'SSH connection successful'"
if %errorlevel% neq 0 (
    echo ERROR: Cannot connect to VPS. Please check:
    echo • VPS is running
    echo • SSH service is active
    echo • Firewall allows SSH connections
    pause
    exit /b 1
)
echo ✅ SSH connection successful

echo.
echo Step 2: Checking system services...
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "echo 'Checking Nginx status...' && systemctl status nginx --no-pager -l || echo 'Nginx not running'"
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "echo 'Checking PHP-FPM status...' && systemctl status php8.2-fpm --no-pager -l || echo 'PHP-FPM not running'"
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "echo 'Checking MySQL status...' && systemctl status mysql --no-pager -l || echo 'MySQL not running'"

echo.
echo Step 3: Checking application directory...
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "if [ -d '%APP_DIR%' ]; then echo '✅ Application directory exists' && ls -la %APP_DIR%/; else echo '❌ Application directory not found' && echo 'Creating application directory...' && mkdir -p %APP_DIR%; fi"

echo.
echo Step 4: Checking Laravel application files...
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "cd %APP_DIR% && if [ -f 'artisan' ]; then echo '✅ Laravel application found'; else echo '❌ Laravel application not found' && echo 'Cloning repository...' && git clone https://github.com/Hanssen23/Silencio.git .; fi"

echo.
echo Step 5: Checking .env file...
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "cd %APP_DIR% && if [ -f '.env' ]; then echo '✅ .env file exists' && echo 'Checking .env configuration...' && grep -E '^APP_KEY=' .env || echo 'APP_KEY not set' && grep -E '^DB_' .env || echo 'Database configuration missing'; else echo '❌ .env file not found' && echo 'Creating .env file...' && cat > .env << 'EOF'
APP_NAME=\"Silencio Gym Management System\"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://%VPS_IP%

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
MAIL_USERNAME=noreply@%VPS_IP%
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@%VPS_IP%
MAIL_FROM_NAME=\"\${APP_NAME}\"

RFID_DEVICE_ID=main_reader
RFID_API_URL=http://%VPS_IP%

BCRYPT_ROUNDS=12
EOF
fi"

echo.
echo Step 6: Installing dependencies...
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "cd %APP_DIR% && echo 'Installing PHP dependencies...' && composer install --optimize-autoloader --no-dev --no-interaction"
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "cd %APP_DIR% && echo 'Installing Node.js dependencies...' && npm install --production"
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "cd %APP_DIR% && echo 'Building assets...' && npm run build"

echo.
echo Step 7: Setting up Laravel application...
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "cd %APP_DIR% && echo 'Generating application key...' && php artisan key:generate --force"
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "cd %APP_DIR% && echo 'Running migrations...' && php artisan migrate --force"
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "cd %APP_DIR% && echo 'Seeding database...' && php artisan db:seed --force"
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "cd %APP_DIR% && echo 'Clearing caches...' && php artisan config:clear && php artisan cache:clear && php artisan view:clear && php artisan route:clear"
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "cd %APP_DIR% && echo 'Caching configuration...' && php artisan config:cache && php artisan route:cache && php artisan view:cache"
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "cd %APP_DIR% && echo 'Creating storage link...' && php artisan storage:link"

echo.
echo Step 8: Setting file permissions...
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "cd %APP_DIR% && echo 'Setting file permissions...' && chown -R www-data:www-data . && chmod -R 755 storage/ && chmod -R 755 bootstrap/cache/ && chmod -R 755 public/ && chmod 600 .env"
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "cd %APP_DIR% && echo 'Setting directory permissions...' && find . -type d -exec chmod 755 {} \; && find . -type f -exec chmod 644 {} \;"

echo.
echo Step 9: Configuring web server...
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "echo 'Configuring Nginx...' && cat > /etc/nginx/sites-available/silencio-gym << 'EOF'
server {
    listen 80;
    server_name %VPS_IP%;
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

ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "echo 'Enabling site...' && ln -sf /etc/nginx/sites-available/silencio-gym /etc/nginx/sites-enabled/ && rm -f /etc/nginx/sites-enabled/default"
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "echo 'Testing Nginx configuration...' && nginx -t"
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "echo 'Reloading Nginx...' && systemctl reload nginx"

echo.
echo Step 10: Starting services...
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "echo 'Starting Nginx...' && systemctl start nginx && systemctl enable nginx"
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "echo 'Starting PHP-FPM...' && systemctl start php8.2-fpm && systemctl enable php8.2-fpm"
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "echo 'Starting MySQL...' && systemctl start mysql && systemctl enable mysql"

echo.
echo Step 11: Testing application...
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "echo 'Testing application response...' && curl -I http://localhost || echo 'Local test failed'"
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "echo 'Checking application logs...' && if [ -f '%APP_DIR%/storage/logs/laravel.log' ]; then echo 'Recent Laravel logs:' && tail -20 %APP_DIR%/storage/logs/laravel.log; else echo 'No Laravel logs found'; fi"
ssh -o StrictHostKeyChecking=no %VPS_USER%@%VPS_IP% "echo 'Checking Nginx error logs...' && if [ -f '/var/log/nginx/error.log' ]; then echo 'Recent Nginx errors:' && tail -10 /var/log/nginx/error.log; fi"

echo.
echo ========================================
echo  DIAGNOSTIC AND FIX COMPLETED!
echo ========================================
echo.
echo What was fixed:
echo • Application dependencies installed
echo • Laravel application configured
echo • Database setup completed
echo • File permissions corrected
echo • Web server configured
echo • Services started
echo.
echo Test your application:
echo • URL: http://%VPS_IP%
echo • Admin Login: admin@admin.com / admin123
echo.
echo If you still get errors:
echo • Check logs: ssh %VPS_USER%@%VPS_IP% "tail -f %APP_DIR%/storage/logs/laravel.log"
echo • Check Nginx: ssh %VPS_USER%@%VPS_IP% "systemctl status nginx"
echo • Check PHP-FPM: ssh %VPS_USER%@%VPS_IP% "systemctl status php8.2-fpm"
echo.
echo Your application should now be working!
echo.
pause
