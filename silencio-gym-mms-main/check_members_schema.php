<?php
$databaseFile = 'database/database.sqlite';

try {
    $pdo = new PDO("sqlite:$databaseFile");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== Members Table Schema ===\n";
    
    // Get table schema
    $stmt = $pdo->query("PRAGMA table_info(members)");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($columns as $column) {
        echo "Column: {$column['name']}, Type: {$column['type']}, Null: {$column['notnull']}, Default: {$column['dflt_value']}\n";
    }
    
    echo "\n=== Sample Members Data ===\n";
    
    // Get sample data
    $stmt = $pdo->query("SELECT * FROM members LIMIT 3");
    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($members)) {
        foreach ($members as $member) {
            echo "Member data: " . json_encode($member, JSON_PRETTY_PRINT) . "\n";
        }
    } else {
        echo "No members found.\n";
    }
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
?>
