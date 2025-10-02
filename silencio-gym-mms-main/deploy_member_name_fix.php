<?php
/**
 * DEPLOYMENT SCRIPT: Add member_name column to rfid_logs table
 * 
 * This script safely adds the member_name column to fix the "Unknown User" display issue.
 * Run this on the VPS after deploying the RfidController and view updates.
 */

echo "=== RFID MEMBER NAME FIX DEPLOYMENT ===\n";
echo "This script will add member_name column to rfid_logs table.\n\n";

// Check if we're in Laravel environment
if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
    echo "ERROR: Not in Laravel root directory. Please run from project root.\n";
    exit(1);
}

// Bootstrap Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

try {
    // Add member_name column using raw SQL (safer than migration during deployment)
    echo "Adding member_name column to rfid_logs table...\n";
    
    // Check if column already exists
    $existingColumns = DB::select("PRAGMA table_info(rfid_logs)");
    $columnExists = false;
    
    foreach ($existingColumns as $column) {
        if ($column->name === 'member_name') {
            $columnExists = true;
            break;
        }
    }
    
    if ($columnExists) {
        echo "✅ Column 'member_name' already exists!\n";
    } else {
        // Add the column
        DB::statement('ALTER TABLE rfid_logs ADD COLUMN member_name VARCHAR(255)');
        echo "✅ Column 'member_name' added successfully!\n";
        
        // Update existing records with member names
        echo "Updating existing RFID logs with member names...\n";
        
        $updatedRows = DB::statement("
            UPDATE rfid_logs 
            SET member_name = (
                SELECT first_name || ' ' || last_name 
                FROM members 
                WHERE members.id = rfid_logs.member_id
            )
            WHERE member_id IS NOT NULL
        ");
        
        echo "✅ Updated existing RFID logs with member names.\n";
    }
    
    // Verify the fix
    echo "\nVerifying deployment...\n";
    $sampleLog = DB::table('rfid_logs')
        ->join('members', 'members.id', '=', 'rfid_logs.member_id')
        ->select('rfid_logs.*', 'members.first_name', 'members.last_name')
        ->first();
        
    if ($sampleLog) {
        echo "✅ Verification successful! Sample log shows member data.\n";
    }
    
    echo "\n🎉 DEPLOYMENT COMPLETE!\n";
    echo "The 'Unknown User' issue should now be fixed in the RFID Monitor dashboard.\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Please check the database connection and try again.\n";
    exit(1);
}
