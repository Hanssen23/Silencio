# Automated Deployment Script for Silencio Gym Management System to Hostinger VPS
# PowerShell version for Windows

param(
    [string]$VPS_IP = "156.67.221.184",
    [string]$VPS_USER = "root",
    [string]$APP_DIR = "/var/www/silencio-gym",
    [string]$DOMAIN = "yourdomain.com",
    [string]$GITHUB_TOKEN = "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",
    [string]$REPO_URL = "https://github.com/Hanssen23/Silencio.git"
)

# Colors for output
$Red = "`e[31m"
$Green = "`e[32m"
$Yellow = "`e[33m"
$Blue = "`e[34m"
$Reset = "`e[0m"

Write-Host ""
Write-Host "$Blue🚀 Starting deployment of Silencio Gym Management System to Hostinger VPS$Reset"
Write-Host "$BlueVPS IP: $VPS_IP$Reset"
Write-Host "$BlueTarget Directory: $APP_DIR$Reset"
Write-Host ""

# Function to run commands on VPS
function Invoke-VPSCommand {
    param([string]$Command)
    ssh -o StrictHostKeyChecking=no "$VPS_USER@$VPS_IP" $Command
}

# Function to copy files to VPS
function Copy-ToVPS {
    param([string]$LocalPath, [string]$RemotePath)
    scp -o StrictHostKeyChecking=no -r $LocalPath "$VPS_USER@$VPS_IP`:$RemotePath"
}

try {
    Write-Host "$Yellow📋 Step 1: Preparing VPS environment...$Reset"
    
    # Update system and install required packages
    Invoke-VPSCommand "apt update && apt upgrade -y && apt install -y nginx php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip php8.2-bcmath php8.2-gd php8.2-sqlite3 composer nodejs npm git unzip mysql-server"
    
    Write-Host "$Green✅ VPS environment prepared$Reset"
    
    Write-Host "$Yellow📋 Step 2: Setting up web server...$Reset"
    
    # Configure Nginx
    $nginxConfig = @"
server {
    listen 80;
    server_name $DOMAIN www.$DOMAIN;
    root $APP_DIR/public;
    index index.php index.html;

    location / {
        try_files `$uri `$uri/ /index.php?`$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME `$realpath_root`$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
"@
    
    Invoke-VPSCommand "cat > /etc/nginx/sites-available/silencio-gym << 'EOF'
$nginxConfig
EOF"
    
    Invoke-VPSCommand "ln -sf /etc/nginx/sites-available/silencio-gym /etc/nginx/sites-enabled/ && rm -f /etc/nginx/sites-enabled/default && nginx -t && systemctl reload nginx"
    
    Write-Host "$Green✅ Nginx configured$Reset"
    
    Write-Host "$Yellow📋 Step 3: Setting up MySQL database...$Reset"
    
    # Create database and user
    Invoke-VPSCommand "mysql -e `"CREATE DATABASE IF NOT EXISTS silencio_gym_db;`" && mysql -e `"CREATE USER IF NOT EXISTS 'silencio_user'@'localhost' IDENTIFIED BY 'SilencioGym2024!';`" && mysql -e `"GRANT ALL PRIVILEGES ON silencio_gym_db.* TO 'silencio_user'@'localhost';`" && mysql -e `"FLUSH PRIVILEGES;`""
    
    Write-Host "$Green✅ MySQL database created$Reset"
    
    Write-Host "$Yellow📋 Step 4: Deploying application...$Reset"
    
    # Create application directory and clone repository
    Invoke-VPSCommand "mkdir -p $APP_DIR"
    Invoke-VPSCommand "cd $APP_DIR && if [ -d '.git' ]; then git pull origin main; else git clone $REPO_URL .; fi"
    
    Write-Host "$Green✅ Application code deployed$Reset"
    
    Write-Host "$Yellow📋 Step 5: Installing dependencies...$Reset"
    
    # Install PHP dependencies
    Invoke-VPSCommand "cd $APP_DIR && composer install --optimize-autoloader --no-dev"
    
    # Install Node.js dependencies and build assets
    Invoke-VPSCommand "cd $APP_DIR && npm install && npm run build"
    
    Write-Host "$Green✅ Dependencies installed$Reset"
    
    Write-Host "$Yellow📋 Step 6: Configuring environment...$Reset"
    
    # Create .env file
    $envConfig = @"
APP_NAME="Silencio Gym Management System"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://$DOMAIN

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
MAIL_USERNAME=noreply@$DOMAIN
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@$DOMAIN
MAIL_FROM_NAME="`${APP_NAME}"

RFID_DEVICE_ID=main_reader
RFID_API_URL=https://$DOMAIN

BCRYPT_ROUNDS=12
"@
    
    Invoke-VPSCommand "cd $APP_DIR && cat > .env << 'EOF'
$envConfig
EOF"
    
    Write-Host "$Green✅ Environment configured$Reset"
    
    Write-Host "$Yellow📋 Step 7: Setting up Laravel application...$Reset"
    
    # Generate application key and run migrations
    Invoke-VPSCommand "cd $APP_DIR && php artisan key:generate --force && php artisan migrate --force && php artisan db:seed --force && php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan storage:link"
    
    Write-Host "$Green✅ Laravel application configured$Reset"
    
    Write-Host "$Yellow📋 Step 8: Setting file permissions...$Reset"
    
    # Set proper permissions
    Invoke-VPSCommand "cd $APP_DIR && chown -R www-data:www-data . && chmod -R 755 storage/ && chmod -R 755 bootstrap/cache/ && chmod -R 755 public/ && chmod 600 .env"
    
    Write-Host "$Green✅ File permissions set$Reset"
    
    Write-Host "$Yellow📋 Step 9: Setting up SSL certificate...$Reset"
    
    # Install Certbot and get SSL certificate
    Invoke-VPSCommand "apt install -y certbot python3-certbot-nginx && certbot --nginx -d $DOMAIN -d www.$DOMAIN --non-interactive --agree-tos --email admin@$DOMAIN"
    
    Write-Host "$Green✅ SSL certificate configured$Reset"
    
    Write-Host "$Yellow📋 Step 10: Setting up RFID system...$Reset"
    
    # Install Python dependencies for RFID
    Invoke-VPSCommand "apt install -y python3 python3-pip && pip3 install pyscard requests"
    
    Write-Host "$Green✅ RFID system prepared$Reset"
    
    Write-Host "$Yellow📋 Step 11: Creating RFID service...$Reset"
    
    # Create RFID service
    $serviceConfig = @"
[Unit]
Description=Silencio Gym RFID Reader Service
After=network.target

[Service]
Type=simple
User=www-data
WorkingDirectory=$APP_DIR
ExecStart=/usr/bin/python3 $APP_DIR/rfid_reader.py --api https://$DOMAIN
Restart=always
RestartSec=10

[Install]
WantedBy=multi-user.target
"@
    
    Invoke-VPSCommand "cat > /etc/systemd/system/silencio-rfid.service << 'EOF'
$serviceConfig
EOF"
    
    Invoke-VPSCommand "systemctl daemon-reload && systemctl enable silencio-rfid.service"
    
    Write-Host "$Green✅ RFID service created$Reset"
    
    Write-Host "$Yellow📋 Step 12: Final optimizations...$Reset"
    
    # Final optimizations
    Invoke-VPSCommand "cd $APP_DIR && composer dump-autoload --optimize && php artisan optimize"
    
    Write-Host "$Green✅ Application optimized$Reset"
    
    Write-Host ""
    Write-Host "$Green🎉 Deployment completed successfully!$Reset"
    Write-Host ""
    Write-Host "$Blue📋 Deployment Summary:$Reset"
    Write-Host "$Blue• Application URL: https://$DOMAIN$Reset"
    Write-Host "$Blue• Admin Login: admin@admin.com / admin123$Reset"
    Write-Host "$Blue• Database: silencio_gym_db$Reset"
    Write-Host "$Blue• Database User: silencio_user$Reset"
    Write-Host "$Blue• Application Directory: $APP_DIR$Reset"
    Write-Host ""
    Write-Host "$Yellow📋 Next Steps:$Reset"
    Write-Host "$Yellow1. Update your domain DNS to point to $VPS_IP$Reset"
    Write-Host "$Yellow2. Test your application at https://$DOMAIN$Reset"
    Write-Host "$Yellow3. Configure email settings in .env file$Reset"
    Write-Host "$Yellow4. Start RFID service: systemctl start silencio-rfid$Reset"
    Write-Host "$Yellow5. Monitor logs: journalctl -u silencio-rfid -f$Reset"
    Write-Host ""
    Write-Host "$Blue🔧 Useful Commands:$Reset"
    Write-Host "$Blue• Check application status: systemctl status nginx php8.2-fpm$Reset"
    Write-Host "$Blue• View application logs: tail -f $APP_DIR/storage/logs/laravel.log$Reset"
    Write-Host "$Blue• Restart services: systemctl restart nginx php8.2-fpm silencio-rfid$Reset"
    Write-Host "$Blue• Update application: cd $APP_DIR && git pull && composer install --no-dev$Reset"
    Write-Host ""
    Write-Host "$Green✅ Your Silencio Gym Management System is now live!$Reset"
    
} catch {
    Write-Host "$Red❌ Deployment failed: $($_.Exception.Message)$Reset"
    Write-Host "$RedPlease check the error and try again.$Reset"
    exit 1
}
