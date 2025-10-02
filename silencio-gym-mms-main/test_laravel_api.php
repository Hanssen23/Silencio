<?php
/**
 * Test Laravel API directly
 */

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Testing Laravel API Directly ===\n";

try {
    $controller = new \App\Http\Controllers\RfidController();
    
    // Test with a known member UID
    $testMember = \App\Models\Member::first();
    if ($testMember) {
        echo "Test member: {$testMember->member_number} ({$testMember->first_name} {$testMember->last_name})\n";
        echo "UID: {$testMember->uid}\n\n";
        
        // Create a request
        $request = \Illuminate\Http\Request::create('/api/rfid/tap', 'POST', [
            'card_uid' => $testMember->uid,
            'device_id' => 'direct_test'
        ]);
        
        // Call the controller directly
        $response = $controller->handleCardTap($request);
        $responseData = $response->getData(true);
        
        echo "Response Status: " . $response->getStatusCode() . "\n";
        echo "Success: " . ($responseData['success'] ? 'TRUE' : 'FALSE') . "\n";
        echo "Message: " . ($responseData['message'] ?? 'N/A') . "\n";
        echo "Action: " . ($responseData['action'] ?? 'N/A') . "\n";
        
        if ($responseData['success']) {
            echo "\n[OK] API is working correctly!\n";
        } else {
            echo "\n[ERROR] API returned error\n";
        }
    } else {
        echo "[ERROR] No test members found\n";
    }
    
} catch (\Exception $e) {
    echo "[ERROR] Test failed: " . $e->getMessage() . "\n";
}

echo "\n=== Test Complete ===\n";
