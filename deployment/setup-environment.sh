#!/bin/bash

# Environment Setup Script for Silencio Gym Management System
# This script prepares your local environment for deployment

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${BLUE}🔧 Setting up environment for Silencio Gym Management System deployment...${NC}"
echo ""

# Check if we're in the right directory
if [ ! -f "composer.json" ]; then
    echo -e "${RED}❌ Error: composer.json not found. Please run this script from the project root directory.${NC}"
    exit 1
fi

echo -e "${YELLOW}📋 Step 1: Checking system requirements...${NC}"

# Check PHP version
if command -v php &> /dev/null; then
    PHP_VERSION=$(php -r "echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;")
    echo -e "${GREEN}✅ PHP $PHP_VERSION found${NC}"
    
    if [ "$(php -r "echo version_compare(PHP_VERSION, '8.2.0', '>=');")" != "1" ]; then
        echo -e "${RED}❌ PHP 8.2 or higher is required. Current version: $PHP_VERSION${NC}"
        exit 1
    fi
else
    echo -e "${RED}❌ PHP not found. Please install PHP 8.2 or higher.${NC}"
    exit 1
fi

# Check Composer
if command -v composer &> /dev/null; then
    echo -e "${GREEN}✅ Composer found${NC}"
else
    echo -e "${RED}❌ Composer not found. Please install Composer.${NC}"
    exit 1
fi

# Check Node.js
if command -v node &> /dev/null; then
    NODE_VERSION=$(node --version)
    echo -e "${GREEN}✅ Node.js $NODE_VERSION found${NC}"
else
    echo -e "${RED}❌ Node.js not found. Please install Node.js.${NC}"
    exit 1
fi

# Check npm
if command -v npm &> /dev/null; then
    echo -e "${GREEN}✅ npm found${NC}"
else
    echo -e "${RED}❌ npm not found. Please install npm.${NC}"
    exit 1
fi

# Check SSH
if command -v ssh &> /dev/null; then
    echo -e "${GREEN}✅ SSH client found${NC}"
else
    echo -e "${RED}❌ SSH client not found. Please install OpenSSH or PuTTY.${NC}"
    exit 1
fi

echo -e "${GREEN}✅ All system requirements met${NC}"

echo -e "${YELLOW}📋 Step 2: Installing PHP dependencies...${NC}"
composer install --optimize-autoloader --no-dev
echo -e "${GREEN}✅ PHP dependencies installed${NC}"

echo -e "${YELLOW}📋 Step 3: Installing Node.js dependencies...${NC}"
npm install
echo -e "${GREEN}✅ Node.js dependencies installed${NC}"

echo -e "${YELLOW}📋 Step 4: Building frontend assets...${NC}"
npm run build
echo -e "${GREEN}✅ Frontend assets built${NC}"

echo -e "${YELLOW}📋 Step 5: Preparing deployment files...${NC}"

# Create deployment package
echo -e "${BLUE}Creating deployment package...${NC}"
tar -czf silencio-gym-deployment.tar.gz \
    --exclude='node_modules' \
    --exclude='.git' \
    --exclude='storage/logs/*' \
    --exclude='storage/framework/cache/*' \
    --exclude='storage/framework/sessions/*' \
    --exclude='storage/framework/views/*' \
    --exclude='.env' \
    --exclude='deploy-to-hostinger.*' \
    --exclude='deployment-config.conf' \
    --exclude='DEPLOYMENT_GUIDE.md' \
    .

echo -e "${GREEN}✅ Deployment package created: silencio-gym-deployment.tar.gz${NC}"

echo -e "${YELLOW}📋 Step 6: Setting up deployment scripts...${NC}"

# Make deployment scripts executable
chmod +x deploy-to-hostinger.sh 2>/dev/null || echo "Bash script not found, skipping..."
chmod +x deploy-to-hostinger.ps1 2>/dev/null || echo "PowerShell script not found, skipping..."

echo -e "${GREEN}✅ Deployment scripts prepared${NC}"

echo ""
echo -e "${GREEN}🎉 Environment setup completed successfully!${NC}"
echo ""
echo -e "${BLUE}📋 What's been prepared:${NC}"
echo -e "${BLUE}• PHP dependencies installed${NC}"
echo -e "${BLUE}• Node.js dependencies installed${NC}"
echo -e "${BLUE}• Frontend assets built${NC}"
echo -e "${BLUE}• Deployment package created${NC}"
echo -e "${BLUE}• Deployment scripts ready${NC}"
echo ""
echo -e "${YELLOW}📋 Next steps:${NC}"
echo -e "${YELLOW}1. Update deployment-config.conf with your domain${NC}"
echo -e "${YELLOW}2. Run the deployment script:${NC}"
echo -e "${YELLOW}   - Windows: .\\deploy-to-hostinger.ps1${NC}"
echo -e "${YELLOW}   - Linux/Mac: ./deploy-to-hostinger.sh${NC}"
echo -e "${YELLOW}3. Follow the deployment guide: DEPLOYMENT_GUIDE.md${NC}"
echo ""
echo -e "${BLUE}🔧 Configuration files:${NC}"
echo -e "${BLUE}• deployment-config.conf - Main configuration${NC}"
echo -e "${BLUE}• DEPLOYMENT_GUIDE.md - Detailed instructions${NC}"
echo -e "${BLUE}• deploy-to-hostinger.* - Deployment scripts${NC}"
echo ""
echo -e "${GREEN}✅ Ready for deployment!${NC}"
