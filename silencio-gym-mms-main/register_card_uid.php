<?php
// Simple script to register the RFID card UID to a member
// This bypasses Laravel's database connection issues

$databaseFile = 'database/database.sqlite';

if (!file_exists($databaseFile)) {
    echo "Database file not found: $databaseFile\n";
    exit(1);
}

try {
    $pdo = new PDO("sqlite:$databaseFile");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== Current Members ===\n";
    
    // Get all members
    $stmt = $pdo->query("SELECT id, full_name, uid, membership_status FROM members ORDER BY id");
    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($members)) {
        echo "No members found in database.\n";
        exit(1);
    }
    
    foreach ($members as $member) {
        echo "ID: {$member['id']}, Name: {$member['full_name']}, UID: " . ($member['uid'] ?: 'NULL') . ", Status: {$member['membership_status']}\n";
    }
    
    echo "\n=== Current Card UID ===\n";
    echo "Detected UID: E69F8F40\n";
    
    // Check if UID is already registered
    $stmt = $pdo->prepare("SELECT id, full_name FROM members WHERE uid = ?");
    $stmt->execute(['E69F8F40']);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($existing) {
        echo "\nCard UID E69F8F40 is already registered to: {$existing['full_name']} (ID: {$existing['id']})\n";
    } else {
        echo "\nCard UID E69F8F40 is NOT registered.\n";
        echo "\n=== Register Card to Member ===\n";
        
        // Register to first member (you can change this)
        $firstMember = $members[0];
        echo "Registering card to: {$firstMember['full_name']} (ID: {$firstMember['id']})\n";
        
        $stmt = $pdo->prepare("UPDATE members SET uid = ? WHERE id = ?");
        $result = $stmt->execute(['E69F8F40', $firstMember['id']]);
        
        if ($result) {
            echo "✅ Card successfully registered!\n";
            echo "Member: {$firstMember['full_name']}\n";
            echo "UID: E69F8F40\n";
        } else {
            echo "❌ Failed to register card.\n";
        }
    }
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
