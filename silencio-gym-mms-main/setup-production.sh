#!/bin/bash

# Stop Laravel development server if running
pkill -f "php artisan serve"

# Stop services
systemctl stop nginx
systemctl stop php8.1-fpm

# Backup existing configuration
timestamp=$(date +%Y%m%d_%H%M%S)
if [ -f /etc/nginx/sites-available/default ]; then
    mv /etc/nginx/sites-available/default /etc/nginx/sites-available/default.backup.$timestamp
fi

# Remove any existing symlinks
rm -f /etc/nginx/sites-enabled/*

# Copy Nginx configuration
cp nginx.conf /etc/nginx/sites-available/silencio
ln -sf /etc/nginx/sites-available/silencio /etc/nginx/sites-enabled/

# Set proper permissions
chown -R www-data:www-data /var/www/silencio-test
find /var/www/silencio-test -type f -exec chmod 644 {} \;
find /var/www/silencio-test -type d -exec chmod 755 {} \;
chmod -R 777 /var/www/silencio-test/storage
chmod -R 777 /var/www/silencio-test/bootstrap/cache

# Configure PHP-FPM
sed -i 's/;listen.mode = 0660/listen.mode = 0660/' /etc/php/8.1/fpm/pool.d/www.conf
sed -i 's/;listen.owner = www-data/listen.owner = www-data/' /etc/php/8.1/fpm/pool.d/www.conf
sed -i 's/;listen.group = www-data/listen.group = www-data/' /etc/php/8.1/fpm/pool.d/www.conf
sed -i 's/user = .*/user = www-data/' /etc/php/8.1/fpm/pool.d/www.conf
sed -i 's/group = .*/group = www-data/' /etc/php/8.1/fpm/pool.d/www.conf
sed -i 's/;clear_env = no/clear_env = no/' /etc/php/8.1/fpm/pool.d/www.conf

# Configure PHP
sed -i 's/memory_limit = .*/memory_limit = 256M/' /etc/php/8.1/fpm/php.ini
sed -i 's/max_execution_time = .*/max_execution_time = 300/' /etc/php/8.1/fpm/php.ini
sed -i 's/post_max_size = .*/post_max_size = 100M/' /etc/php/8.1/fpm/php.ini
sed -i 's/upload_max_filesize = .*/upload_max_filesize = 100M/' /etc/php/8.1/fpm/php.ini

# Clear Laravel caches
cd /var/www/silencio-test
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Enable and restart services
systemctl enable nginx
systemctl enable php8.1-fpm
systemctl restart php8.1-fpm
systemctl restart nginx

# Test Nginx configuration
nginx -t

# Show service status
echo "Service Status:"
systemctl status nginx --no-pager
systemctl status php8.1-fpm --no-pager

# Test PHP-FPM socket
if [ -S /var/run/php/php8.1-fpm.sock ]; then
    echo "PHP-FPM socket exists and is a socket file"
else
    echo "PHP-FPM socket does not exist or is not a socket file"
fi
