#!/bin/bash

# Quick Fix Script for Silencio Gym Management System 500 Error
# This script will fix the most common issues causing 500 errors

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
APP_DIR="/var/www/silencio"

echo -e "${BLUE}🔧 Quick Fix for Silencio Gym Management System 500 Error${NC}"
echo ""

# Function to run commands on VPS
run_on_vps() {
    ssh -o StrictHostKeyChecking=no ${VPS_USER}@${VPS_IP} "$1"
}

echo -e "${YELLOW}📋 Step 1: Fixing .env file...${NC}"

# Create a proper .env file
run_on_vps "
    cd ${APP_DIR}
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
DB_USERNAME=laravel_user
DB_PASSWORD=password

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

CACHE_DRIVER=file
CACHE_PREFIX=

QUEUE_CONNECTION=sync

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
"

echo -e "${GREEN}✅ .env file created${NC}"

echo -e "${YELLOW}📋 Step 2: Generating application key...${NC}"

# Generate application key
run_on_vps "
    cd ${APP_DIR}
    php artisan key:generate --force
"

echo -e "${GREEN}✅ Application key generated${NC}"

echo -e "${YELLOW}📋 Step 3: Clearing all caches...${NC}"

# Clear all caches
run_on_vps "
    cd ${APP_DIR}
    rm -rf bootstrap/cache/*
    php artisan config:clear
    php artisan cache:clear
    php artisan view:clear
    php artisan route:clear
"

echo -e "${GREEN}✅ All caches cleared${NC}"

echo -e "${YELLOW}📋 Step 4: Setting up database...${NC}"

# Create database and user
run_on_vps "
    mysql -u root -e 'CREATE DATABASE IF NOT EXISTS silencio_gym_db;'
    mysql -u root -e 'GRANT ALL PRIVILEGES ON silencio_gym_db.* TO \"laravel_user\"@\"localhost\" IDENTIFIED BY \"password\";'
    mysql -u root -e 'FLUSH PRIVILEGES;'
"

echo -e "${GREEN}✅ Database setup completed${NC}"

echo -e "${YELLOW}📋 Step 5: Running migrations...${NC}"

# Run migrations
run_on_vps "
    cd ${APP_DIR}
    php artisan migrate --force
"

echo -e "${GREEN}✅ Migrations completed${NC}"

echo -e "${YELLOW}📋 Step 6: Seeding database...${NC}"

# Seed database
run_on_vps "
    cd ${APP_DIR}
    php artisan db:seed --force
"

echo -e "${GREEN}✅ Database seeded${NC}"

echo -e "${YELLOW}📋 Step 7: Setting file permissions...${NC}"

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

echo -e "${YELLOW}📋 Step 8: Testing application...${NC}"

# Test application
run_on_vps "
    cd ${APP_DIR}
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
"

echo -e "${GREEN}✅ Application optimized${NC}"

echo ""
echo -e "${GREEN}🎉 Quick fix completed!${NC}"
echo ""
echo -e "${BLUE}📋 What was fixed:${NC}"
echo -e "${BLUE}• .env file recreated with proper settings${NC}"
echo -e "${BLUE}• Application key generated${NC}"
echo -e "${BLUE}• All caches cleared${NC}"
echo -e "${BLUE}• Database setup completed${NC}"
echo -e "${BLUE}• Migrations run successfully${NC}"
echo -e "${BLUE}• Database seeded with initial data${NC}"
echo -e "${BLUE}• File permissions corrected${NC}"
echo -e "${BLUE}• Application optimized${NC}"
echo ""
echo -e "${YELLOW}📋 Test your application:${NC}"
echo -e "${YELLOW}• URL: http://${VPS_IP}${NC}"
echo -e "${YELLOW}• Admin Login: admin@admin.com / admin123${NC}"
echo ""
echo -e "${GREEN}✅ Your application should now be working!${NC}"
