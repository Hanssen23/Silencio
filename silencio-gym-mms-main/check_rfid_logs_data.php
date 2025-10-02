<?php
// Check RFID logs table data directly
try {
    $pdo = new PDO('sqlite:database/database.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== RFID Logs Data ===\n";
    
    // Check table structure
    echo "Table structure:\n";
    $stmt = $pdo->query("PRAGMA table_info(rfid_logs)");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  {$row['name']} ({$row['type']})\n";
    }
    
    echo "\n=== Recent RFID Logs for E69F8F40 ===\n";
    $query = $pdo->prepare('SELECT * FROM rfid_logs WHERE card_uid = ? ORDER BY timestamp DESC LIMIT 5');
    $query->execute(['E69F8F40']);
    
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        echo "ID: {$row['id']}\n";
        echo "Member ID: {$row['member_id']}\n";
        echo "Member Name: {$row['member_name']}\n";
        echo "Card UID: {$row['card_uid']}\n";
        echo "Action: {$row['action']}\n";
        echo "Timestamp: {$row['timestamp']}\n";
        echo "---\n";
    }
    
    echo "\n=== Recent All RFID Logs ===\n";
    $query = $pdo->prepare('SELECT * FROM rfid_logs ORDER BY timestamp DESC LIMIT 5');
    $query->execute();
    
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        echo "ID: {$row['id']}, Member: {$row['member_name']}, Card: {$row['card_uid']}, Action: {$row['action']}, Time: {$row['timestamp']}\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
