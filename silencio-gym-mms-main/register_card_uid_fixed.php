<?php
$databaseFile = 'database/database.sqlite';

try {
    $pdo = new PDO("sqlite:$databaseFile");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== Current Members ===\n";
    
    // Get all members
    $stmt = $pdo->query("SELECT id, first_name, last_name, uid, status FROM members ORDER BY id");
    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($members as $member) {
        $name = trim($member['first_name'] . ' ' . $member['last_name']);
        echo "ID: {$member['id']}, Name: {$name}, UID: " . ($member['uid'] ?: 'NULL') . ", Status: {$member['status']}\n";
    }
    
    echo "\n=== Current Card UID ===\n";
    echo "Detected UID: E69F8F40\n";
    
    // Check if UID is already registered
    $stmt = $pdo->prepare("SELECT id, first_name, last_name FROM members WHERE uid = ?");
    $stmt->execute(['E69F8F40']);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($existing) {
        $name = trim($existing['first_name'] . ' ' . $existing['last_name']);
        echo "\nCard UID E69F8F40 is already registered to: {$name} (ID: {$existing['id']})\n";
    } else {
        echo "\nCard UID E69F8F40 is NOT registered.\n";
        echo "\n=== Register Card to Member ===\n";
        
        // Register to first member (you can change this)
        $firstMember = $members[0];
        $name = trim($firstMember['first_name'] . ' ' . $firstMember['last_name']);
        echo "Registering card to: {$name} (ID: {$firstMember['id']})\n";
        
        $stmt = $pdo->prepare("UPDATE members SET uid = ? WHERE id = ?");
        $result = $stmt->execute(['E69F8F40', $firstMember['id']]);
        
        if ($result) {
            echo "✅ Card successfully registered!\n";
            echo "Member: {$name}\n";
            echo "UID: E69F8F40\n";
        } else {
            echo "❌ Failed to register card.\n";
        }
    }
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
?>
