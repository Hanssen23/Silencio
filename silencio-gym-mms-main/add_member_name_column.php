<?php
// Add member_name column to rfid_logs table
try {
    $pdo = new PDO('sqlite:database/database.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Adding member_name column to rfid_logs table...\n";
    
    // Check if column already exists
    $stmt = $pdo->query("PRAGMA table_info(rfid_logs)");
    $columns = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $columns[] = $row['name'];
    }
    
    if (in_array('member_name', $columns)) {
        echo "Column 'member_name' already exists!\n";
        exit;
    }
    
    // Add the column
    $pdo->exec('ALTER TABLE rfid_logs ADD COLUMN member_name VARCHAR(255)');
    
    echo "Column 'member_name' added successfully!\n";
    
    // Update existing records to populate member_name
    echo "Updating existing records...\n";
    
    $stmt = $pdo->prepare("
        UPDATE rfid_logs 
        SET member_name = (
            SELECT COALESCE(m.first_name || ' ' || m.last_name, 'Unknown Member')
            FROM members m 
            WHERE m.id = rfid_logs.member_id
        )
        WHERE member_id IS NOT NULL
    ");
    
    $stmt->execute();
    $affectedRows = $stmt->rowCount();
    echo "Updated {$affectedRows} records with member names.\n";
    
    // Show a sample
    echo "\nSample updated records:\n";
    $stmt = $pdo->query("SELECT member_name, card_uid, action, timestamp FROM rfid_logs WHERE member_name IS NOT NULL ORDER BY timestamp DESC LIMIT 3");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "Member: {$row['member_name']}, Card: {$row['card_uid']}, Action: {$row['action']}, Time: {$row['timestamp']}\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
