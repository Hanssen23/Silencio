<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Set Admin Credentials ===\n";
echo "Timestamp: " . now() . "\n";
echo "============================\n\n";

// Step 1: Check if admin user exists
echo "=== Step 1: Checking for existing admin user ===\n";
$admin = \App\Models\User::where('email', 'admin@admin.com')->first();

if ($admin) {
    echo "Admin user found: ID {$admin->id}\n";
    echo "Current name: {$admin->name}\n";
    echo "Current email: {$admin->email}\n";
    echo "Current role: {$admin->role}\n";
    echo "Last updated: {$admin->updated_at}\n\n";
    
    // Step 2: Update existing admin
    echo "=== Step 2: Updating existing admin user ===\n";
    $admin->update([
        'name' => 'Administrator',
        'email' => 'admin@admin.com',
        'password' => bcrypt('admin123'),
        'role' => 'admin',
        'email_verified_at' => now(),
    ]);
    
    echo "✅ Admin user updated successfully!\n";
    echo "Email: admin@admin.com\n";
    echo "Password: admin123\n";
    echo "Role: admin\n\n";
    
} else {
    echo "No admin user found. Creating new admin user...\n\n";
    
    // Step 2: Create new admin user
    echo "=== Step 2: Creating new admin user ===\n";
    $admin = \App\Models\User::create([
        'name' => 'Administrator',
        'email' => 'admin@admin.com',
        'password' => bcrypt('admin123'),
        'role' => 'admin',
        'email_verified_at' => now(),
    ]);
    
    echo "✅ Admin user created successfully!\n";
    echo "ID: {$admin->id}\n";
    echo "Name: {$admin->name}\n";
    echo "Email: {$admin->email}\n";
    echo "Password: admin123\n";
    echo "Role: {$admin->role}\n";
    echo "Created: {$admin->created_at}\n\n";
}

// Step 3: Verify admin credentials
echo "=== Step 3: Verifying admin credentials ===\n";
$testUser = \App\Models\User::where('email', 'admin@admin.com')->first();

if ($testUser && \Hash::check('admin123', $testUser->password)) {
    echo "✅ Password verification successful!\n";
    echo "Admin can login with:\n";
    echo "  Email: admin@admin.com\n";
    echo "  Password: admin123\n\n";
} else {
    echo "❌ Password verification failed!\n";
    echo "Please check the password hashing.\n\n";
}

// Step 4: Check admin permissions
echo "=== Step 4: Checking admin permissions ===\n";
echo "Admin role: {$testUser->role}\n";
echo "Email verified: " . ($testUser->email_verified_at ? 'Yes' : 'No') . "\n";
echo "Account status: " . ($testUser->status ?? 'Active') . "\n\n";

// Step 5: Test login URL
echo "=== Step 5: Login Information ===\n";
echo "You can now login at:\n";
echo "  URL: http://localhost:8000/login\n";
echo "  Email: admin@admin.com\n";
echo "  Password: admin123\n\n";

// Step 6: Additional admin setup
echo "=== Step 6: Additional Admin Setup ===\n";
echo "The admin user has been configured with:\n";
echo "  - Full administrator privileges\n";
echo "  - Access to all system features\n";
echo "  - Ability to manage members, payments, and RFID system\n";
echo "  - Dashboard access and monitoring capabilities\n\n";

echo "=== Admin Setup Complete ===\n";
echo "You can now access the admin panel with the credentials above.\n";
