#!/bin/bash

# Automated Deployment Script for Silencio Gym Management System to Hostinger VPS
# This script will deploy your Laravel application to your Hostinger VPS

set -e  # Exit on any error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
VPS_IP="156.67.221.184"
VPS_USER="root"
APP_DIR="/var/www/silencio-gym"
DOMAIN="yourdomain.com"  # Replace with your actual domain
GITHUB_TOKEN="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"
REPO_URL="https://github.com/Hanssen23/Silencio.git"

echo -e "${BLUE}🚀 Starting deployment of Silencio Gym Management System to Hostinger VPS${NC}"
echo -e "${BLUE}VPS IP: ${VPS_IP}${NC}"
echo -e "${BLUE}Target Directory: ${APP_DIR}${NC}"
echo ""

# Function to run commands on VPS
run_on_vps() {
    ssh -o StrictHostKeyChecking=no ${VPS_USER}@${VPS_IP} "$1"
}

# Function to copy files to VPS
copy_to_vps() {
    scp -o StrictHostKeyChecking=no -r "$1" ${VPS_USER}@${VPS_IP}:"$2"
}

echo -e "${YELLOW}📋 Step 1: Preparing VPS environment...${NC}"

# Update system and install required packages
run_on_vps "
    apt update && apt upgrade -y
    apt install -y nginx php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip php8.2-bcmath php8.2-gd php8.2-sqlite3
    apt install -y composer nodejs npm git unzip
    apt install -y mysql-server
"

echo -e "${GREEN}✅ VPS environment prepared${NC}"

echo -e "${YELLOW}📋 Step 2: Setting up web server...${NC}"

# Configure Nginx
run_on_vps "
    cat > /etc/nginx/sites-available/silencio-gym << 'EOF'
server {
    listen 80;
    server_name ${DOMAIN} www.${DOMAIN};
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

    ln -sf /etc/nginx/sites-available/silencio-gym /etc/nginx/sites-enabled/
    rm -f /etc/nginx/sites-enabled/default
    nginx -t && systemctl reload nginx
"

echo -e "${GREEN}✅ Nginx configured${NC}"

echo -e "${YELLOW}📋 Step 3: Setting up MySQL database...${NC}"

# Create database and user
run_on_vps "
    mysql -e \"CREATE DATABASE IF NOT EXISTS silencio_gym_db;\"
    mysql -e \"CREATE USER IF NOT EXISTS 'silencio_user'@'localhost' IDENTIFIED BY 'SilencioGym2024!';\" 
    mysql -e \"GRANT ALL PRIVILEGES ON silencio_gym_db.* TO 'silencio_user'@'localhost';\"
    mysql -e \"FLUSH PRIVILEGES;\"
"

echo -e "${GREEN}✅ MySQL database created${NC}"

echo -e "${YELLOW}📋 Step 4: Deploying application...${NC}"

# Create application directory
run_on_vps "mkdir -p ${APP_DIR}"

# Clone repository
run_on_vps "
    cd ${APP_DIR}
    if [ -d '.git' ]; then
        git pull origin main
    else
        git clone ${REPO_URL} .
    fi
"

echo -e "${GREEN}✅ Application code deployed${NC}"

echo -e "${YELLOW}📋 Step 5: Installing dependencies...${NC}"

# Install PHP dependencies
run_on_vps "
    cd ${APP_DIR}
    composer install --optimize-autoloader --no-dev
"

# Install Node.js dependencies and build assets
run_on_vps "
    cd ${APP_DIR}
    npm install
    npm run build
"

echo -e "${GREEN}✅ Dependencies installed${NC}"

echo -e "${YELLOW}📋 Step 6: Configuring environment...${NC}"

# Create .env file
run_on_vps "
    cd ${APP_DIR}
    cat > .env << 'EOF'
APP_NAME=\"Silencio Gym Management System\"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://${DOMAIN}

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
MAIL_USERNAME=noreply@${DOMAIN}
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@${DOMAIN}
MAIL_FROM_NAME=\"\${APP_NAME}\"

RFID_DEVICE_ID=main_reader
RFID_API_URL=https://${DOMAIN}

BCRYPT_ROUNDS=12
EOF
"

echo -e "${GREEN}✅ Environment configured${NC}"

echo -e "${YELLOW}📋 Step 7: Setting up Laravel application...${NC}"

# Generate application key and run migrations
run_on_vps "
    cd ${APP_DIR}
    php artisan key:generate --force
    php artisan migrate --force
    php artisan db:seed --force
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan storage:link
"

echo -e "${GREEN}✅ Laravel application configured${NC}"

echo -e "${YELLOW}📋 Step 8: Setting file permissions...${NC}"

# Set proper permissions
run_on_vps "
    cd ${APP_DIR}
    chown -R www-data:www-data .
    chmod -R 755 storage/
    chmod -R 755 bootstrap/cache/
    chmod -R 755 public/
    chmod 600 .env
"

echo -e "${GREEN}✅ File permissions set${NC}"

echo -e "${YELLOW}📋 Step 9: Setting up SSL certificate...${NC}"

# Install Certbot and get SSL certificate
run_on_vps "
    apt install -y certbot python3-certbot-nginx
    certbot --nginx -d ${DOMAIN} -d www.${DOMAIN} --non-interactive --agree-tos --email admin@${DOMAIN}
"

echo -e "${GREEN}✅ SSL certificate configured${NC}"

echo -e "${YELLOW}📋 Step 10: Setting up RFID system (optional)...${NC}"

# Install Python dependencies for RFID
run_on_vps "
    apt install -y python3 python3-pip
    pip3 install pyscard requests
"

echo -e "${GREEN}✅ RFID system prepared${NC}"

echo -e "${YELLOW}📋 Step 11: Creating systemd service for RFID...${NC}"

# Create RFID service
run_on_vps "
    cat > /etc/systemd/system/silencio-rfid.service << 'EOF'
[Unit]
Description=Silencio Gym RFID Reader Service
After=network.target

[Service]
Type=simple
User=www-data
WorkingDirectory=${APP_DIR}
ExecStart=/usr/bin/python3 ${APP_DIR}/rfid_reader.py --api https://${DOMAIN}
Restart=always
RestartSec=10

[Install]
WantedBy=multi-user.target
EOF

    systemctl daemon-reload
    systemctl enable silencio-rfid.service
"

echo -e "${GREEN}✅ RFID service created${NC}"

echo -e "${YELLOW}📋 Step 12: Final optimizations...${NC}"

# Final optimizations
run_on_vps "
    cd ${APP_DIR}
    composer dump-autoload --optimize
    php artisan optimize
"

echo -e "${GREEN}✅ Application optimized${NC}"

echo ""
echo -e "${GREEN}🎉 Deployment completed successfully!${NC}"
echo ""
echo -e "${BLUE}📋 Deployment Summary:${NC}"
echo -e "${BLUE}• Application URL: https://${DOMAIN}${NC}"
echo -e "${BLUE}• Admin Login: admin@admin.com / admin123${NC}"
echo -e "${BLUE}• Database: silencio_gym_db${NC}"
echo -e "${BLUE}• Database User: silencio_user${NC}"
echo -e "${BLUE}• Application Directory: ${APP_DIR}${NC}"
echo ""
echo -e "${YELLOW}📋 Next Steps:${NC}"
echo -e "${YELLOW}1. Update your domain DNS to point to ${VPS_IP}${NC}"
echo -e "${YELLOW}2. Test your application at https://${DOMAIN}${NC}"
echo -e "${YELLOW}3. Configure email settings in .env file${NC}"
echo -e "${YELLOW}4. Start RFID service: systemctl start silencio-rfid${NC}"
echo -e "${YELLOW}5. Monitor logs: journalctl -u silencio-rfid -f${NC}"
echo ""
echo -e "${BLUE}🔧 Useful Commands:${NC}"
echo -e "${BLUE}• Check application status: systemctl status nginx php8.2-fpm${NC}"
echo -e "${BLUE}• View application logs: tail -f ${APP_DIR}/storage/logs/laravel.log${NC}"
echo -e "${BLUE}• Restart services: systemctl restart nginx php8.2-fpm silencio-rfid${NC}"
echo -e "${BLUE}• Update application: cd ${APP_DIR} && git pull && composer install --no-dev${NC}"
echo ""
echo -e "${GREEN}✅ Your Silencio Gym Management System is now live!${NC}"
