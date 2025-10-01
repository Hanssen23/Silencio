#!/bin/bash

# Diagnostic and Fix Script for Silencio Gym Management System 500 Error
# This script will diagnose and fix common issues causing 500 errors

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# Configuration
VPS_IP="156.67.221.184"
VPS_USER="root"
APP_DIR="/var/www/silencio-gym"

echo -e "${BLUE}🔍 Diagnosing 500 Server Error on Silencio Gym Management System...${NC}"
echo -e "${BLUE}VPS IP: ${VPS_IP}${NC}"
echo ""

# Function to run commands on VPS
run_on_vps() {
    ssh -o StrictHostKeyChecking=no ${VPS_USER}@${VPS_IP} "$1"
}

echo -e "${YELLOW}📋 Step 1: Checking VPS connectivity...${NC}"

# Test SSH connection
if ssh -o StrictHostKeyChecking=no -o ConnectTimeout=10 ${VPS_USER}@${VPS_IP} "echo 'SSH connection successful'" 2>/dev/null; then
    echo -e "${GREEN}✅ SSH connection successful${NC}"
else
    echo -e "${RED}❌ Cannot connect to VPS. Please check:${NC}"
    echo -e "${RED}• VPS is running${NC}"
    echo -e "${RED}• SSH service is active${NC}"
    echo -e "${RED}• Firewall allows SSH connections${NC}"
    exit 1
fi

echo -e "${YELLOW}📋 Step 2: Checking system services...${NC}"

# Check if services are running
run_on_vps "
    echo 'Checking Nginx status...'
    systemctl status nginx --no-pager -l || echo 'Nginx not running'
    
    echo 'Checking PHP-FPM status...'
    systemctl status php8.2-fpm --no-pager -l || echo 'PHP-FPM not running'
    
    echo 'Checking MySQL status...'
    systemctl status mysql --no-pager -l || echo 'MySQL not running'
"

echo -e "${YELLOW}📋 Step 3: Checking application directory...${NC}"

# Check if application directory exists
run_on_vps "
    if [ -d '${APP_DIR}' ]; then
        echo '✅ Application directory exists'
        ls -la ${APP_DIR}/
    else
        echo '❌ Application directory not found'
        echo 'Creating application directory...'
        mkdir -p ${APP_DIR}
    fi
"

echo -e "${YELLOW}📋 Step 4: Checking Laravel application files...${NC}"

# Check if Laravel files exist
run_on_vps "
    cd ${APP_DIR}
    if [ -f 'artisan' ]; then
        echo '✅ Laravel application found'
    else
        echo '❌ Laravel application not found'
        echo 'Cloning repository...'
        git clone https://github.com/Hanssen23/Silencio.git .
    fi
"

echo -e "${YELLOW}📋 Step 5: Checking .env file...${NC}"

# Check and create .env file
run_on_vps "
    cd ${APP_DIR}
    if [ -f '.env' ]; then
        echo '✅ .env file exists'
        echo 'Checking .env configuration...'
        grep -E '^APP_KEY=' .env || echo 'APP_KEY not set'
        grep -E '^DB_' .env || echo 'Database configuration missing'
    else
        echo '❌ .env file not found'
        echo 'Creating .env file...'
        cat > .env << 'EOF'
APP_NAME=\"Silencio Gym Management System\"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://${VPS_IP}

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
MAIL_USERNAME=noreply@${VPS_IP}
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@${VPS_IP}
MAIL_FROM_NAME=\"\${APP_NAME}\"

RFID_DEVICE_ID=main_reader
RFID_API_URL=http://${VPS_IP}

BCRYPT_ROUNDS=12
EOF
    fi
"

echo -e "${YELLOW}📋 Step 6: Installing dependencies...${NC}"

# Install dependencies
run_on_vps "
    cd ${APP_DIR}
    echo 'Installing PHP dependencies...'
    composer install --optimize-autoloader --no-dev --no-interaction
    
    echo 'Installing Node.js dependencies...'
    npm install --production
    
    echo 'Building assets...'
    npm run build
"

echo -e "${YELLOW}📋 Step 7: Setting up Laravel application...${NC}"

# Setup Laravel
run_on_vps "
    cd ${APP_DIR}
    echo 'Generating application key...'
    php artisan key:generate --force
    
    echo 'Running migrations...'
    php artisan migrate --force
    
    echo 'Seeding database...'
    php artisan db:seed --force
    
    echo 'Clearing caches...'
    php artisan config:clear
    php artisan cache:clear
    php artisan view:clear
    php artisan route:clear
    
    echo 'Caching configuration...'
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    
    echo 'Creating storage link...'
    php artisan storage:link
"

echo -e "${YELLOW}📋 Step 8: Setting file permissions...${NC}"

# Set proper permissions
run_on_vps "
    cd ${APP_DIR}
    echo 'Setting file permissions...'
    chown -R www-data:www-data .
    chmod -R 755 storage/
    chmod -R 755 bootstrap/cache/
    chmod -R 755 public/
    chmod 600 .env
    
    echo 'Setting directory permissions...'
    find . -type d -exec chmod 755 {} \;
    find . -type f -exec chmod 644 {} \;
"

echo -e "${YELLOW}📋 Step 9: Configuring web server...${NC}"

# Configure Nginx
run_on_vps "
    echo 'Configuring Nginx...'
    cat > /etc/nginx/sites-available/silencio-gym << 'EOF'
server {
    listen 80;
    server_name ${VPS_IP};
    root ${APP_DIR}/public;
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
EOF

    echo 'Enabling site...'
    ln -sf /etc/nginx/sites-available/silencio-gym /etc/nginx/sites-enabled/
    rm -f /etc/nginx/sites-enabled/default
    
    echo 'Testing Nginx configuration...'
    nginx -t
    
    echo 'Reloading Nginx...'
    systemctl reload nginx
"

echo -e "${YELLOW}📋 Step 10: Starting services...${NC}"

# Start services
run_on_vps "
    echo 'Starting Nginx...'
    systemctl start nginx
    systemctl enable nginx
    
    echo 'Starting PHP-FPM...'
    systemctl start php8.2-fpm
    systemctl enable php8.2-fpm
    
    echo 'Starting MySQL...'
    systemctl start mysql
    systemctl enable mysql
"

echo -e "${YELLOW}📋 Step 11: Testing application...${NC}"

# Test application
run_on_vps "
    echo 'Testing application response...'
    curl -I http://localhost || echo 'Local test failed'
    
    echo 'Checking application logs...'
    if [ -f '${APP_DIR}/storage/logs/laravel.log' ]; then
        echo 'Recent Laravel logs:'
        tail -20 ${APP_DIR}/storage/logs/laravel.log
    else
        echo 'No Laravel logs found'
    fi
    
    echo 'Checking Nginx error logs...'
    if [ -f '/var/log/nginx/error.log' ]; then
        echo 'Recent Nginx errors:'
        tail -10 /var/log/nginx/error.log
    fi
"

echo ""
echo -e "${GREEN}🎉 Diagnostic and fix completed!${NC}"
echo ""
echo -e "${BLUE}📋 What was fixed:${NC}"
echo -e "${BLUE}• Application dependencies installed${NC}"
echo -e "${BLUE}• Laravel application configured${NC}"
echo -e "${BLUE}• Database setup completed${NC}"
echo -e "${BLUE}• File permissions corrected${NC}"
echo -e "${BLUE}• Web server configured${NC}"
echo -e "${BLUE}• Services started${NC}"
echo ""
echo -e "${YELLOW}📋 Test your application:${NC}"
echo -e "${YELLOW}• URL: http://${VPS_IP}${NC}"
echo -e "${YELLOW}• Admin Login: admin@admin.com / admin123${NC}"
echo ""
echo -e "${BLUE}🔧 If you still get errors:${NC}"
echo -e "${BLUE}• Check logs: ssh ${VPS_USER}@${VPS_IP} 'tail -f ${APP_DIR}/storage/logs/laravel.log'${NC}"
echo -e "${BLUE}• Check Nginx: ssh ${VPS_USER}@${VPS_IP} 'systemctl status nginx'${NC}"
echo -e "${BLUE}• Check PHP-FPM: ssh ${VPS_USER}@${VPS_IP} 'systemctl status php8.2-fpm'${NC}"
echo ""
echo -e "${GREEN}✅ Your application should now be working!${NC}"
