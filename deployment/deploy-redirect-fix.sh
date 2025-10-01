#!/bin/bash

# Deploy redirect loop fix to server
echo "🚀 Deploying redirect loop fix to server..."

# Server details
SERVER_IP="156.67.221.184"
SERVER_USER="root"
APP_DIR="/var/www/silencio-gym"

# Function to upload file
upload_file() {
    local local_file="$1"
    local remote_path="$2"
    echo "📤 Uploading $local_file to $remote_path"
    scp "$local_file" "$SERVER_USER@$SERVER_IP:$remote_path"
}

# Upload the fixed .htaccess file (MAIN FIX)
echo "🔧 Uploading fixed .htaccess file (disables HTTPS redirect)..."
upload_file ".htaccess" "$APP_DIR/"

# Upload the fixed middleware files
echo "🔧 Uploading fixed middleware files..."
upload_file "silencio-gym-mms-main/app/Http/Controllers/AuthController.php" "$APP_DIR/app/Http/Controllers/"
upload_file "silencio-gym-mms-main/app/Http/Middleware/Authenticate.php" "$APP_DIR/app/Http/Middleware/"
upload_file "silencio-gym-mms-main/app/Http/Middleware/AdminOnly.php" "$APP_DIR/app/Http/Middleware/"
upload_file "silencio-gym-mms-main/app/Http/Middleware/EmployeeOnly.php" "$APP_DIR/app/Http/Middleware/"
upload_file "silencio-gym-mms-main/app/Http/Middleware/MemberOnly.php" "$APP_DIR/app/Http/Middleware/"

# Upload missing provider files
echo "🔧 Uploading missing provider files..."
upload_file "silencio-gym-mms-main/app/Providers/AuthServiceProvider.php" "$APP_DIR/app/Providers/"
upload_file "silencio-gym-mms-main/app/Providers/EventServiceProvider.php" "$APP_DIR/app/Providers/"
upload_file "silencio-gym-mms-main/app/Providers/RouteServiceProvider.php" "$APP_DIR/app/Providers/"
upload_file "silencio-gym-mms-main/routes/api.php" "$APP_DIR/routes/"

# Upload missing middleware
echo "🔧 Uploading missing RedirectIfAuthenticated middleware..."
upload_file "silencio-gym-mms-main/app/Http/Middleware/RedirectIfAuthenticated.php" "$APP_DIR/app/Http/Middleware/"

# Clear caches on server
echo "🧹 Clearing caches on server..."
ssh "$SERVER_USER@$SERVER_IP" "cd $APP_DIR && php artisan config:clear && php artisan route:clear && php artisan cache:clear"

# Restart web server
echo "🔄 Restarting web server..."
ssh "$SERVER_USER@$SERVER_IP" "systemctl restart nginx php8.2-fpm"

echo "✅ Deployment completed successfully!"
echo ""
echo "🎯 The redirect loop should now be fixed!"
echo "🌐 Test your application at: http://$SERVER_IP:8001"
echo ""
echo "📋 What was fixed:"
echo "   • Disabled HTTPS redirect in .htaccess (main cause)"
echo "   • Added redirect loop prevention in middleware"
echo "   • Created missing Laravel provider files"
echo "   • Added missing RedirectIfAuthenticated middleware"
echo "   • Cleared all Laravel caches"
echo ""
echo "🔍 If you still see issues:"
echo "   1. Clear your browser cookies for the site"
echo "   2. Try incognito/private browsing mode"
echo "   3. Check server logs: ssh $SERVER_USER@$SERVER_IP 'tail -f $APP_DIR/storage/logs/laravel.log'"
