<?php
/**
 * Complete RFID System Test
 * Tests the entire RFID system functionality
 */

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Complete RFID System Test ===\n";
echo "Timestamp: " . now() . "\n";
echo "===============================\n\n";

class RfidSystemTester
{
    private $testResults = [];
    private $errors = [];
    
    public function runAllTests()
    {
        echo "🔍 Testing Complete RFID System...\n\n";
        
        $this->testDatabaseModels();
        $this->testRfidController();
        $this->testApiEndpoints();
        $this->testPythonScripts();
        $this->testHardwareCompatibility();
        $this->testMemberFlow();
        $this->displayResults();
    }
    
    private function testDatabaseModels()
    {
        echo "=== Test 1: Database Models ===\n";
        
        try {
            // Test Member model
            $member = new \App\Models\Member();
            echo "✅ Member model: OK\n";
            $this->testResults['member_model'] = true;
            
            // Test RfidLog model
            $rfidLog = new \App\Models\RfidLog();
            echo "✅ RfidLog model: OK\n";
            $this->testResults['rfid_log_model'] = true;
            
            // Test ActiveSession model
            $activeSession = new \App\Models\ActiveSession();
            echo "✅ ActiveSession model: OK\n";
            $this->testResults['active_session_model'] = true;
            
            // Test Attendance model
            $attendance = new \App\Models\Attendance();
            echo "✅ Attendance model: OK\n";
            $this->testResults['attendance_model'] = true;
            
            // Test relationships
            $member = \App\Models\Member::first();
            if ($member) {
                $activeSessions = $member->activeSessions();
                $attendances = $member->attendances();
                $rfidLogs = $member->rfidLogs();
                echo "✅ Model relationships: OK\n";
                $this->testResults['model_relationships'] = true;
            }
            
        } catch (\Exception $e) {
            echo "❌ Database models test failed: " . $e->getMessage() . "\n";
            $this->errors[] = "Database models: " . $e->getMessage();
        }
        
        echo "\n";
    }
    
    private function testRfidController()
    {
        echo "=== Test 2: RfidController ===\n";
        
        try {
            $controller = new \App\Http\Controllers\RfidController();
            echo "✅ RfidController instantiated\n";
            $this->testResults['controller_instantiation'] = true;
            
            // Test all required methods
            $requiredMethods = [
                'handleCardTap',
                'getActiveMembers',
                'getRfidLogs',
                'getDashboardStats',
                'handleCheckIn',
                'handleCheckOut',
                'logRfidEvent',
                'startRfidReader',
                'stopRfidReader',
                'getRfidStatus'
            ];
            
            foreach ($requiredMethods as $method) {
                if (method_exists($controller, $method)) {
                    echo "✅ Method {$method}: OK\n";
                } else {
                    echo "❌ Method {$method}: Missing\n";
                    $this->errors[] = "Missing method: {$method}";
                }
            }
            
            $this->testResults['controller_methods'] = true;
            
        } catch (\Exception $e) {
            echo "❌ RfidController test failed: " . $e->getMessage() . "\n";
            $this->errors[] = "RfidController: " . $e->getMessage();
        }
        
        echo "\n";
    }
    
    private function testApiEndpoints()
    {
        echo "=== Test 3: API Endpoints ===\n";
        
        try {
            $controller = new \App\Http\Controllers\RfidController();
            
            // Test getActiveMembers
            $response = $controller->getActiveMembers();
            if ($response->getStatusCode() === 200) {
                echo "✅ getActiveMembers API: OK\n";
                $this->testResults['api_active_members'] = true;
            } else {
                echo "❌ getActiveMembers API: Failed (Status: {$response->getStatusCode()})\n";
                $this->errors[] = "getActiveMembers API failed";
            }
            
            // Test getRfidLogs
            $request = new \Illuminate\Http\Request();
            $response = $controller->getRfidLogs($request);
            if ($response->getStatusCode() === 200) {
                echo "✅ getRfidLogs API: OK\n";
                $this->testResults['api_logs'] = true;
            } else {
                echo "❌ getRfidLogs API: Failed (Status: {$response->getStatusCode()})\n";
                $this->errors[] = "getRfidLogs API failed";
            }
            
            // Test getDashboardStats
            $response = $controller->getDashboardStats();
            if ($response->getStatusCode() === 200) {
                echo "✅ getDashboardStats API: OK\n";
                $this->testResults['api_stats'] = true;
            } else {
                echo "❌ getDashboardStats API: Failed (Status: {$response->getStatusCode()})\n";
                $this->errors[] = "getDashboardStats API failed";
            }
            
        } catch (\Exception $e) {
            echo "❌ API endpoints test failed: " . $e->getMessage() . "\n";
            $this->errors[] = "API endpoints: " . $e->getMessage();
        }
        
        echo "\n";
    }
    
    private function testPythonScripts()
    {
        echo "=== Test 4: Python Scripts ===\n";
        
        try {
            // Check if rfid_reader.py exists
            if (file_exists('rfid_reader.py')) {
                echo "✅ rfid_reader.py: Found\n";
                $this->testResults['python_script'] = true;
                
                // Check Python syntax
                $output = shell_exec('python -m py_compile rfid_reader.py 2>&1');
                if (empty($output)) {
                    echo "✅ Python syntax: Valid\n";
                    $this->testResults['python_syntax'] = true;
                } else {
                    echo "❌ Python syntax error: {$output}\n";
                    $this->errors[] = "Python syntax error";
                }
            } else {
                echo "❌ rfid_reader.py: Not found\n";
                $this->errors[] = "rfid_reader.py not found";
            }
            
            // Check if rfid_config.json exists
            if (file_exists('rfid_config.json')) {
                echo "✅ rfid_config.json: Found\n";
                $this->testResults['config_file'] = true;
                
                // Validate JSON
                $config = json_decode(file_get_contents('rfid_config.json'), true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    echo "✅ Config JSON: Valid\n";
                    $this->testResults['config_json'] = true;
                } else {
                    echo "❌ Config JSON: Invalid\n";
                    $this->errors[] = "Invalid JSON in config file";
                }
            } else {
                echo "❌ rfid_config.json: Not found\n";
                $this->errors[] = "rfid_config.json not found";
            }
            
            // Check if requirements.txt exists
            if (file_exists('requirements.txt')) {
                echo "✅ requirements.txt: Found\n";
                $this->testResults['requirements_file'] = true;
            } else {
                echo "❌ requirements.txt: Not found\n";
                $this->errors[] = "requirements.txt not found";
            }
            
        } catch (\Exception $e) {
            echo "❌ Python scripts test failed: " . $e->getMessage() . "\n";
            $this->errors[] = "Python scripts: " . $e->getMessage();
        }
        
        echo "\n";
    }
    
    private function testHardwareCompatibility()
    {
        echo "=== Test 5: Hardware Compatibility ===\n";
        
        try {
            // Check Python libraries
            $libraries = ['pyscard', 'requests', 'smartcard'];
            $availableLibraries = 0;
            
            foreach ($libraries as $lib) {
                $output = shell_exec("python -c \"import {$lib}; print('OK')\" 2>&1");
                if (strpos($output, 'OK') !== false) {
                    echo "✅ Python library {$lib}: Available\n";
                    $availableLibraries++;
                } else {
                    echo "❌ Python library {$lib}: Not available\n";
                    $this->errors[] = "Python library {$lib} not available";
                }
            }
            
            if ($availableLibraries === count($libraries)) {
                $this->testResults['python_libraries'] = true;
            }
            
            // Check Python version
            $pythonVersion = shell_exec('python --version 2>&1');
            if ($pythonVersion) {
                echo "✅ Python version: " . trim($pythonVersion) . "\n";
                $this->testResults['python_version'] = true;
            } else {
                echo "❌ Python: Not found\n";
                $this->errors[] = "Python not found";
            }
            
            // Check if ACR122U might be connected
            if (PHP_OS_FAMILY === 'Windows') {
                $output = shell_exec('wmic path win32_pnpentity where "name like \'%ACR122U%\'" get name 2>&1');
                if (strpos($output, 'ACR122U') !== false) {
                    echo "✅ ACR122U device: Detected\n";
                    $this->testResults['hardware_acr122u'] = true;
                } else {
                    echo "⚠️  ACR122U device: Not detected (may not be connected)\n";
                }
            } else {
                echo "⚠️  ACR122U detection: Not supported on this OS\n";
            }
            
        } catch (\Exception $e) {
            echo "❌ Hardware compatibility test failed: " . $e->getMessage() . "\n";
            $this->errors[] = "Hardware compatibility: " . $e->getMessage();
        }
        
        echo "\n";
    }
    
    private function testMemberFlow()
    {
        echo "=== Test 6: Member Check-in/Check-out Flow ===\n";
        
        try {
            // Find a test member
            $testMember = \App\Models\Member::first();
            if (!$testMember) {
                echo "❌ No members found for testing\n";
                $this->errors[] = "No members found for testing";
                return;
            }
            
            echo "✅ Test member found: {$testMember->member_number} ({$testMember->first_name} {$testMember->last_name})\n";
            echo "   UID: {$testMember->uid}\n";
            
            // Test RFID tap simulation
            $controller = new \App\Http\Controllers\RfidController();
            $request = \Illuminate\Http\Request::create('/api/rfid/tap', 'POST', [
                'card_uid' => $testMember->uid,
                'device_id' => 'test_system'
            ]);
            
            $response = $controller->handleCardTap($request);
            $responseData = $response->getData(true);
            
            echo "✅ RFID tap simulation: " . ($responseData['success'] ? 'Success' : 'Failed') . "\n";
            echo "   Message: " . ($responseData['message'] ?? 'N/A') . "\n";
            echo "   Action: " . ($responseData['action'] ?? 'N/A') . "\n";
            
            if ($responseData['success']) {
                $this->testResults['member_flow'] = true;
            } else {
                $this->errors[] = "Member flow test failed: " . ($responseData['message'] ?? 'Unknown error');
            }
            
        } catch (\Exception $e) {
            echo "❌ Member flow test failed: " . $e->getMessage() . "\n";
            $this->errors[] = "Member flow: " . $e->getMessage();
        }
        
        echo "\n";
    }
    
    private function displayResults()
    {
        echo "=== Test Results Summary ===\n";
        
        $totalTests = count($this->testResults);
        $passedTests = count(array_filter($this->testResults));
        $failedTests = count($this->errors);
        
        echo "Total Tests: {$totalTests}\n";
        echo "Passed: {$passedTests}\n";
        echo "Failed: {$failedTests}\n";
        echo "Success Rate: " . round(($passedTests / max($totalTests, 1)) * 100, 2) . "%\n\n";
        
        if (count($this->errors) > 0) {
            echo "❌ Errors Found:\n";
            foreach ($this->errors as $error) {
                echo "  - {$error}\n";
            }
            echo "\n";
        }
        
        if ($passedTests === $totalTests && count($this->errors) === 0) {
            echo "🎉 All tests passed! RFID system is ready.\n";
            echo "\nNext steps:\n";
            echo "1. Connect ACR122U reader to USB port\n";
            echo "2. Install Python dependencies: pip install -r requirements.txt\n";
            echo "3. Start RFID reader: python rfid_reader.py\n";
            echo "4. Open RFID Monitor: http://localhost:8000/rfid-monitor\n";
            echo "5. Test with RFID cards\n";
        } else {
            echo "⚠️  Some tests failed. Please address the issues above.\n";
        }
        
        echo "\n=== Test Complete ===\n";
    }
}

// Run the tests
$tester = new RfidSystemTester();
$tester->runAllTests();
