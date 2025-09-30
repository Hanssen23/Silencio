#!/bin/bash

# Dual Database Authentication System Setup Script
# Deploys Python authentication system to Laravel project

set -e

echo "🚀 Setting up Dual Database Authentication System..."

# Configuration
PROJECT_DIR="/var/www/silencio-test"
PYTHON_AUTH_DIR="$PROJECT_DIR/admin_auth_system"
PYTHON_API_PORT=8002

# Check if running as root
if [ "$EUID" -ne 0 ]; then
    echo "❌ Please run as root (use sudo)"
    exit 1
fi

# Create Python authentication directory
echo "📁 Creating Python authentication system directory..."
mkdir -p "$PYTHON_AUTH_DIR/databases"
cd "$PYTHON_AUTH_DIR"

# Copy Python files
echo "📄 Copying Python authentication files..."
cp /root/admin_database.py .
cp /root/corruption_detector.py .
cp /root/auth_manager.py .
cp /root/admin_api.py .
cp /root/config.py .
cp /root/requirements.txt .

# Set proper permissions
chmod +x *.py
chown -R www-data:www-data "$PYTHON_AUTH_DIR"

# Install Python dependencies
echo "🐍 Installing Python dependencies..."
apt update
apt install -y python3 python3-pip python3-venv

# Create virtual environment
python3 -m venv venv
source venv/bin/activate

# Install requirements
pip install -r requirements.txt

# Initialize admin database
echo "🗄️ Initializing admin database..."
python3 admin_database.py

# Create systemd service for Python API
echo "⚙️ Creating systemd service..."
cat > /etc/systemd/system/dual-auth-api.service << EOF
[Unit]
Description=Dual Database Authentication API
After=network.target

[Service]
Type=simple
User=www-data
Group=www-data
WorkingDirectory=$PYTHON_AUTH_DIR
Environment=PATH=$PYTHON_AUTH_DIR/venv/bin
ExecStart=$PYTHON_AUTH_DIR/venv/bin/python admin_api.py
Restart=always
RestartSec=10

[Install]
WantedBy=multi-user.target
EOF

# Enable and start the service
systemctl daemon-reload
systemctl enable dual-auth-api.service
systemctl start dual-auth-api.service

# Create cron job for health checks
echo "⏰ Setting up health check cron job..."
cat > /etc/cron.d/dual-auth-health-check << EOF
# Run health check every 5 minutes
*/5 * * * * www-data cd $PYTHON_AUTH_DIR && python3 -c "from corruption_detector import CorruptionDetector; CorruptionDetector().schedule_health_checks()" >> /var/log/dual-auth-health.log 2>&1
EOF

# Create backup cron job
echo "💾 Setting up backup cron job..."
cat > /etc/cron.d/dual-auth-backup << EOF
# Backup admin database every hour
0 * * * * www-data cd $PYTHON_AUTH_DIR && python3 -c "from admin_database import AdminDatabase; AdminDatabase().backup_database()" >> /var/log/dual-auth-backup.log 2>&1
EOF

# Copy Laravel integration files
echo "🔗 Setting up Laravel integration..."
cp /root/DualAuthService.php "$PROJECT_DIR/app/Services/"
cp /root/DualAuthMiddleware.php "$PROJECT_DIR/app/Http/Middleware/"

# Update Laravel configuration
echo "⚙️ Updating Laravel configuration..."

# Add dual auth configuration to config/auth.php
cat >> "$PROJECT_DIR/config/auth.php" << 'EOF'

    /*
    |--------------------------------------------------------------------------
    | Dual Database Authentication
    |--------------------------------------------------------------------------
    |
    | Configuration for dual database authentication system
    |
    */
    'dual_api_url' => env('DUAL_AUTH_API_URL', 'http://127.0.0.1:8002/api'),
    'dual_api_timeout' => env('DUAL_AUTH_API_TIMEOUT', 10),
EOF

# Add environment variables
echo "📝 Adding environment variables..."
cat >> "$PROJECT_DIR/.env" << EOF

# Dual Database Authentication
DUAL_AUTH_API_URL=http://127.0.0.1:8002/api
DUAL_AUTH_API_TIMEOUT=10
EOF

# Update bootstrap/app.php to register middleware
echo "🔧 Registering middleware..."
if ! grep -q "DualAuthMiddleware" "$PROJECT_DIR/bootstrap/app.php"; then
    sed -i '/->withMiddleware(function (Middleware \$middleware): void {/a\
            $middleware->alias([\
                "dual.auth" => \\App\\Http\\Middleware\\DualAuthMiddleware::class,\
                "dual.admin" => \\App\\Http\\Middleware\\DualAdminOnly::class,\
                "dual.employee" => \\App\\Http\\Middleware\\DualEmployeeOnly::class,\
                "dual.member" => \\App\\Http\\Middleware\\DualMemberOnly::class,\
            ]);' "$PROJECT_DIR/bootstrap/app.php"
fi

# Clear Laravel caches
echo "🧹 Clearing Laravel caches..."
cd "$PROJECT_DIR"
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Test the setup
echo "🧪 Testing the setup..."

# Test Python API
sleep 5  # Wait for service to start
if curl -s http://localhost:$PYTHON_API_PORT/api/health > /dev/null; then
    echo "✅ Python API is running"
else
    echo "❌ Python API failed to start"
    systemctl status dual-auth-api.service
fi

# Test admin database
cd "$PYTHON_AUTH_DIR"
if python3 -c "from admin_database import AdminDatabase; print('Admin DB:', len(AdminDatabase().get_all_admins()), 'admins')"; then
    echo "✅ Admin database is working"
else
    echo "❌ Admin database test failed"
fi

# Test health check
if python3 -c "from corruption_detector import CorruptionDetector; print('Health:', CorruptionDetector().run_health_check()['overall_health'])"; then
    echo "✅ Health check is working"
else
    echo "❌ Health check test failed"
fi

echo ""
echo "🎉 Dual Database Authentication System Setup Complete!"
echo ""
echo "📋 System Information:"
echo "   • Python API: http://localhost:$PYTHON_API_PORT"
echo "   • Admin Database: $PYTHON_AUTH_DIR/databases/admin_auth.db"
echo "   • Backup Database: $PYTHON_AUTH_DIR/databases/backup_admin_auth.db"
echo "   • Service: dual-auth-api.service"
echo "   • Health Check Log: /var/log/dual-auth-health.log"
echo "   • Backup Log: /var/log/dual-auth-backup.log"
echo ""
echo "🔑 Default Admin Accounts:"
echo "   • admin@gmail.com / admin123"
echo "   • adminjed@gmail.com / jed12345"
echo "   • emergency@admin.com / EmergencyAdmin123!"
echo ""
echo "🚀 Next Steps:"
echo "   1. Test login at http://156.67.221.184:8001"
echo "   2. Check API health: curl http://localhost:$PYTHON_API_PORT/api/health"
echo "   3. Monitor logs: tail -f /var/log/dual-auth-health.log"
echo "   4. Use dual auth middleware in your routes"
echo ""
echo "📚 Usage Examples:"
echo "   Route::middleware(['dual.admin'])->group(function () {"
echo "       Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);"
echo "   });"
echo ""
echo "✨ Setup completed successfully!"
