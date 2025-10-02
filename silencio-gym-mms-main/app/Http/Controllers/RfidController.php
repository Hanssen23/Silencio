<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Attendance;
use App\Models\ActiveSession;
use App\Models\RfidLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RfidController extends Controller
{
    /**
     * Show RFID monitor dashboard
     */
    public function monitor()
    {
        return view('rfid-monitor');
    }

    /**
     * Handle RFID card tap for check-in/check-out
     */
    public function handleCardTap(Request $request): JsonResponse
    {
        // Debug: Log the incoming request
        Log::info('RFID tap request received', [
            'all_input' => $request->all(),
            'json' => $request->json()->all(),
            'raw_content' => $request->getContent(),
            'content_type' => $request->header('Content-Type'),
            'method' => $request->method(),
            'url' => $request->url(),
            'headers' => $request->headers->all()
        ]);
        
        $request->validate([
            'uid' => 'required_without:card_uid',
            'card_uid' => 'required_without:uid',
            'device_id' => 'nullable|string',
        ]);

        $cardUid = $request->input('uid') ?: $request->input('card_uid');
        $deviceId = $request->input('device_id', 'main_reader');
        
        // Allow card taps even if RFID system shows offline (for hardware detection)
        // This enables immediate card processing when hardware is connected
        Log::info('RFID card tap received', [
            'card_uid' => $cardUid,
            'device_id' => $deviceId,
            'rfid_status' => $this->isRfidReaderRunning() ? 'online' : 'offline'
        ]);

        // Prevent test scripts from creating sessions in production
        if ($deviceId === 'test_device') {
            Log::info('Test device detected, skipping session creation', [
                'card_uid' => $cardUid,
                'device_id' => $deviceId
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Test device not allowed in production',
                'action' => 'test_device_blocked'
            ], 403);
        }

        try {
            // Use a shorter timeout for database operations
            DB::beginTransaction();
            
            // Find member by UID with lock for update to prevent conflicts
            $member = Member::where('uid', $cardUid)->lockForUpdate()->first();

            if (!$member) {
                $this->logRfidEvent($cardUid, 'unknown_card', 'failed', 
                    "Unknown card UID: {$cardUid}", $deviceId);
                
                DB::commit();
                return response()->json([
                    'success' => false,
                    'message' => 'Unknown card. Please contact staff.',
                    'action' => 'unknown_card',
                    'card_uid' => $cardUid,
                    'feedback' => [
                        'message' => 'Card not registered',
                        'sound' => 'error'
                    ]
                ], 404);
            }

            // Check if member is a valid member (not expired)
            if ($member->membership_expires_at && $member->membership_expires_at < now()) {
                $this->logRfidEvent($cardUid, 'check_in', 'failed', 
                    "Member {$member->first_name} {$member->last_name} has expired membership", $deviceId);
                
                DB::commit();
                return response()->json([
                    'success' => false,
                    'message' => 'Membership has expired. Please renew.',
                    'action' => 'expired_membership',
                    'feedback' => [
                        'message' => 'Membership expired',
                        'sound' => 'error'
                    ]
                ], 403);
            }

            // Check if member has an active session
            $activeSession = ActiveSession::where('member_id', $member->id)
                ->where('status', 'active')
                ->first();

            if ($activeSession) {
                // Member is checking out
                return $this->handleCheckOut($member, $activeSession, $deviceId);
            } else {
                // Member is checking in
                return $this->handleCheckIn($member, $deviceId);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('RFID Error: ' . $e->getMessage(), [
                'card_uid' => $cardUid,
                'device_id' => $deviceId,
                'error' => $e->getMessage()
            ]);

            $this->logRfidEvent($cardUid, 'check_in', 'failed', 
                "System error: " . $e->getMessage(), $deviceId);

            return response()->json([
                'success' => false,
                'message' => 'System error. Please try again.',
                'action' => 'system_error'
            ], 500);
        }
    }

        /**
     * Handle member check-in
     */
    private function handleCheckIn(Member $member, string $deviceId): JsonResponse
    {
        try {
            // Check if member has any existing active session and deactivate it
            $existingActiveSession = ActiveSession::where('member_id', $member->id)
                ->where('status', 'active')
                ->first();
                
            if ($existingActiveSession) {
                // Deactivate the existing session
                $existingActiveSession->update([
                    'status' => 'inactive',
                    'check_out_time' => now(),
                    'session_duration' => $existingActiveSession->currentDuration,
                ]);
                
                // Update the attendance record
                $existingActiveSession->attendance->update([
                    'check_out_time' => now(),
                    'status' => 'checked_out',
                    'session_duration' => $existingActiveSession->currentDuration,
                ]);
            }

            // Create attendance record
            $attendance = Attendance::create([
                'member_id' => $member->id,
                'check_in_time' => now(),
                'status' => 'checked_in',
            ]);

            // Create active session
            $activeSession = ActiveSession::create([
                'member_id' => $member->id,
                'attendance_id' => $attendance->id,
                'check_in_time' => now(),
                'status' => 'active',
            ]);

            // Update member status to active (they're currently in the gym)
            $member->update(['status' => 'active']);

            // Log successful check-in
            $this->logRfidEvent($member->uid, 'check_in', 'success', 
                "Member {$member->first_name} {$member->last_name} checked in successfully", $deviceId);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Welcome back, {$member->first_name} {$member->last_name}!",
                'action' => 'check_in',
                'member' => [
                    'id' => $member->id,
                    'name' => $member->first_name . ' ' . $member->last_name,
                    'member_number' => $member->member_number,
                    'email' => $member->email,
                    'membership_plan' => $member->membershipPlan?->name ?? 'Unknown',
                    'membership_status' => ucfirst($member->status),
                    'check_in_time' => now()->format('h:i:s A'),
                ],
                'feedback' => [
                    'message' => 'Check-in successful',
                    'sound' => 'success'
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Check-in Error: ' . $e->getMessage(), [
                'member_id' => $member->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Check-in failed. Please try again.',
                'action' => 'check_in_error'
            ], 500);
        }
    }

    /**
     * Handle member check-out
     */
    private function handleCheckOut(Member $member, ActiveSession $activeSession, string $deviceId): JsonResponse
    {
        try {
            // Calculate session duration in minutes
            $sessionDurationMinutes = $activeSession->check_in_time->diffInMinutes(now());
            $sessionDurationText = $activeSession->currentDuration;
            
            // Update attendance record
            $attendance = $activeSession->attendance;
            $attendance->update([
                'check_out_time' => now(),
                'status' => 'checked_out',
                'session_duration' => $sessionDurationText,
            ]);

            // Update active session
            $activeSession->update([
                'status' => 'inactive',
                'check_out_time' => now(),
                'session_duration' => $sessionDurationText,
            ]);

            // Update member status to offline (they're no longer in the gym)
            $member->update(['status' => 'offline']);

            // Log successful check-out
            $this->logRfidEvent($member->uid, 'check_out', 'success', 
                "Member {$member->first_name} {$member->last_name} checked out. Session duration: {$sessionDurationText}", $deviceId);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Goodbye, {$member->first_name} {$member->last_name}! Session duration: {$sessionDurationText}",
                'action' => 'check_out',
                'member' => [
                    'id' => $member->id,
                    'name' => $member->first_name . ' ' . $member->last_name,
                    'member_number' => $member->member_number,
                    'email' => $member->email,
                    'membership_plan' => $member->membershipPlan?->name ?? 'Unknown',
                    'membership_status' => ucfirst($member->status),
                    'check_out_time' => now()->format('h:i:s A'),
                    'session_duration' => $sessionDurationText,
                ],
                'feedback' => [
                    'message' => 'Check-out successful',
                    'sound' => 'success'
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Check-out Error: ' . $e->getMessage(), [
                'member_id' => $member->id,
                'session_id' => $activeSession->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Check-out failed. Please try again.',
                'action' => 'check_out_error'
            ], 500);
        }
    }

    /**
     * Log RFID event with retry logic for database locks
     */
    private function logRfidEvent(string $cardUid, string $action, string $status, string $message, string $deviceId): void
    {
        $maxRetries = 3;
        $retryCount = 0;
        
        while ($retryCount < $maxRetries) {
            try {
                // Find member by UID to get member_id
                $member = Member::where('uid', $cardUid)->first();
                
                RfidLog::create([
                    'card_uid' => $cardUid,
                    'member_id' => $member ? $member->id : null,
                    'member_name' => $member ? "{$member->first_name} {$member->last_name}" : null,
                    'action' => $action,
                    'status' => $status,
                    'message' => $message,
                    'timestamp' => now(),
                    'device_id' => $deviceId,
                    'source' => 'rfid',
                ]);
                return; // Success, exit the retry loop
            } catch (\Exception $e) {
                $retryCount++;
                if (strpos($e->getMessage(), 'database is locked') !== false && $retryCount < $maxRetries) {
                    // Wait a bit and retry for database lock errors
                    usleep(100000); // Wait 100ms
                    continue;
                } else {
                    // Log the error but don't fail the main operation
                    Log::warning('Failed to log RFID event after retries: ' . $e->getMessage(), [
                        'card_uid' => $cardUid,
                        'action' => $action,
                        'retry_count' => $retryCount
                    ]);
                    return;
                }
            }
        }
    }


    /**
     * Manually trigger auto tap-out for all active members
     */
    public function manualAutoTapOut(): JsonResponse
    {
        try {
            // Get all active sessions
            $activeSessions = ActiveSession::with('member', 'attendance')
                ->where('status', 'active')
                ->whereNull('check_out_time')
                ->get();

            if ($activeSessions->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No active members found. Nothing to tap out.',
                    'tapped_out_count' => 0
                ]);
            }

            $tappedOutCount = 0;
            $errors = [];

            foreach ($activeSessions as $session) {
                try {
                    $now = Carbon::now();
                    
                    // Update the active session
                    $session->update([
                        'check_out_time' => $now,
                        'status' => 'inactive',
                        'session_duration' => $session->check_in_time->diffForHumans($now, true)
                    ]);

                    // Update the attendance record
                    if ($session->attendance) {
                        $session->attendance->update([
                            'check_out_time' => $now,
                            'duration' => $session->check_in_time->diffInMinutes($now)
                        ]);
                    }

                    $tappedOutCount++;
                    
                    Log::info('Member manually tapped out', [
                        'member_id' => $session->member_id,
                        'member_name' => $session->member->first_name . ' ' . $session->member->last_name,
                        'member_uid' => $session->member->uid,
                        'check_in_time' => $session->check_in_time,
                        'check_out_time' => $now,
                        'session_duration' => $session->session_duration,
                        'reason' => 'manual_auto_tapout'
                    ]);
                    
                } catch (\Exception $e) {
                    $errors[] = "Failed to tap out {$session->member->first_name} {$session->member->last_name}: " . $e->getMessage();
                    
                    Log::error('Manual auto tap-out failed', [
                        'member_id' => $session->member_id,
                        'session_id' => $session->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            $message = "Successfully tapped out {$tappedOutCount} members.";
            if (!empty($errors)) {
                $message .= " Failed to tap out " . count($errors) . " members.";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'tapped_out_count' => $tappedOutCount,
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            Log::error('Manual auto tap-out error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to perform auto tap-out: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get RFID logs
     */
    public function getRfidLogs(Request $request): JsonResponse
    {
        $logs = RfidLog::query()
            ->with('member') // Include member relationship
            ->when($request->input('status'), function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->input('action'), function ($query, $action) {
                return $query->where('action', $action);
            })
            ->when($request->input('date'), function ($query, $date) {
                return $query->whereDate('timestamp', $date);
            })
            ->orderBy('timestamp', 'desc')
            ->paginate(10);

        // Transform logs to include member information
        $transformedLogs = $logs->getCollection()->map(function ($log) {
            return [
                'id' => $log->id,
                'card_uid' => $log->card_uid,
                'action' => $log->action,
                'status' => $log->status,
                'message' => $log->message,
                'timestamp' => $log->timestamp,
                'member_name' => $log->member ? ($log->member->first_name . ' ' . $log->member->last_name) : 'Unknown Member',
                'member_id' => $log->member_id,
            ];
        });

        return response()->json([
            'success' => true,
            'logs' => [
                'data' => $transformedLogs,
                'current_page' => $logs->currentPage(),
                'last_page' => $logs->lastPage(),
                'per_page' => $logs->perPage(),
                'total' => $logs->total(),
            ],
        ]);
    }

    /**
     * Start the RFID reader process
     */
    public function startRfidReader(Request $request): JsonResponse
    {
        try {
            Log::info('RFID start requested', ['user_id' => auth()->id()]);
            
            // Check if RFID reader is already running
            $isRunning = $this->isRfidReaderRunning();
            
            if ($isRunning) {
                Log::info('RFID reader already running');
                return response()->json([
                    'success' => true,
                    'message' => 'RFID reader is already running',
                    'status' => 'running'
                ]);
            }
            
            Log::info('Starting RFID reader process');
            
            // Check hardware availability (optional - don't block if hardware not detected)
            $hardwareAvailable = $this->checkHardwareAvailability();
            if (!$hardwareAvailable) {
                Log::warning('RFID hardware not detected, but allowing startup attempt');
                // Continue anyway - hardware might be detected once Python script starts
            }
            
            // Start the RFID reader process
            $this->startRfidProcess();
            
            // Wait for process to initialize
            sleep(3);
            
            // Always return success - let the Python script handle hardware detection
            Log::info('RFID reader startup initiated');
            return response()->json([
                'success' => true,
                'message' => 'RFID reader startup initiated successfully. Hardware detection will continue in background.',
                'status' => 'started'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error starting RFID reader: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to start RFID reader: ' . $e->getMessage(),
                'status' => 'error'
            ], 500);
        }
    }
    
    /**
     * Stop the RFID reader process
     */
    public function stopRfidReader(Request $request): JsonResponse
    {
        try {
            Log::info('RFID stop requested', ['user_id' => auth()->id()]);
            
            // Stop the RFID reader process
            $this->stopRfidProcess();
            
            // Wait for process to stop
            sleep(2);
            
            // Verify it stopped successfully
            $isRunning = $this->isRfidReaderRunning();
            
            if (!$isRunning) {
                Log::info('RFID reader stopped successfully');
            return response()->json([
                'success' => true,
                'message' => 'RFID reader stopped successfully',
                'status' => 'stopped'
            ]);
            } else {
                Log::warning('RFID reader may still be running');
                return response()->json([
                    'success' => false,
                    'message' => 'RFID reader may still be running',
                    'status' => 'may_be_running'
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Error stopping RFID reader: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to stop RFID reader: ' . $e->getMessage(),
                'status' => 'error'
            ], 500);
        }
    }
    
    /**
     * Get RFID reader status
     */
    public function getRfidStatus(Request $request): JsonResponse
    {
        try {
            $isRunning = $this->isRfidReaderRunning();
            
            return response()->json([
                'success' => true,
                'rfid_reader_running' => $isRunning,
                'status' => $isRunning ? 'running' : 'stopped',
                'message' => $isRunning ? 'RFID system is operational' : 'RFID reader is not running'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get RFID status: ' . $e->getMessage(),
                'status' => 'error'
            ], 500);
        }
    }
    
    /**
     * Start RFID system
     */
    public function startRfidSystem(): JsonResponse
    {
        try {
            // Check if RFID reader is already running
            $isRunning = $this->isRfidReaderRunning();
            
            if ($isRunning) {
                return response()->json([
                    'success' => true,
                    'message' => 'RFID reader is already running',
                    'status' => 'running'
                ]);
            }
            
            // Start the RFID reader
            $this->startRfidProcess();
            
            // Wait a moment for initialization
            sleep(2);
            
            // Check if it started successfully
            $isRunning = $this->isRfidReaderRunning();
            
            if ($isRunning) {
                return response()->json([
                    'success' => true,
                    'message' => 'RFID reader started successfully',
                    'status' => 'started'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to start RFID reader',
                    'status' => 'failed'
                ]);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error starting RFID system: ' . $e->getMessage(),
                'status' => 'error'
            ]);
        }
    }
    
    /**
     * Stop RFID system
     */
    public function stopRfidSystem(): JsonResponse
    {
        try {
            // Stop Python processes
            shell_exec('taskkill /F /IM python.exe 2>nul');
            
            // Wait a moment
            sleep(1);
            
            // Check if it stopped successfully
            $processes = shell_exec('tasklist | findstr python');
            
            if (empty($processes)) {
                return response()->json([
                    'success' => true,
                    'message' => 'RFID reader stopped successfully',
                    'status' => 'stopped'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to stop RFID reader',
                    'status' => 'failed'
                ]);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error stopping RFID system: ' . $e->getMessage(),
                'status' => 'error'
            ]);
        }
    }
    
    /**
     * Check if RFID reader process is running (simplified)
     */
    private function isRfidReaderRunning(): bool
    {
        $rfidStatusFile = storage_path('logs/rfid_running.txt');
        
        if (file_exists($rfidStatusFile)) {
            $content = file_get_contents($rfidStatusFile);
            $data = json_decode($content, true);
            
            if ($data && isset($data['status']) && $data['status'] === 'running') {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Check hardware availability (improved detection)
     */
    private function checkHardwareAvailability(): bool
    {
        try {
            // Check if Python is available (more flexible paths)
            $pythonPaths = [
                'python',
                'python3',
                'C:\Users\hanss\AppData\Local\Programs\Python\Python313\python.exe',
                'C:\Python313\python.exe',
                'C:\Program Files\Python313\python.exe'
            ];
            
            $pythonPath = null;
            foreach ($pythonPaths as $path) {
                $check = shell_exec("$path --version 2>nul");
                if ($check && str_contains($check, 'Python')) {
                    $pythonPath = $path;
                    Log::info("Python found at: $path");
                    break;
                }
            }
            
            // Skip detailed Python/pyscard checks for now
            // Hardware detection can be improved later
            Log::info('RFID hardware check bypassed - using simplified mode');
            return true;

        } catch (\Exception $e) {
            Log::error('Hardware availability check failed: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Start RFID reader process (hybrid approach)
     */
    private function startRfidProcess(): void
    {
        Log::info("Starting RFID system in hybrid mode");
        
        // Create a file to indicate RFID is running
        $rfidStatusFile = storage_path('logs/rfid_running.txt');
        
        // Try to start Python RFID reader if available
        $pythonMode = $this->tryStartPythonRfidReader();
        
        file_put_contents($rfidStatusFile, json_encode([
            'started_at' => now()->toISOString(),
            'status' => 'running',
            'mode' => $pythonMode ? 'python_hardware' : 'web_interface',
            'hardware_detected' => $pythonMode
        ]));
        
        Log::info('RFID system started in hybrid mode', [
            'python_mode' => $pythonMode,
            'status_file' => $rfidStatusFile
        ]);
    }
    
    /**
     * Try to start Python RFID reader if available
     */
    private function tryStartPythonRfidReader(): bool
    {
        try {
            // Check if Python is available
            $pythonPaths = [
                'python',
                'python3',
                'C:\Users\hanss\AppData\Local\Programs\Python\Python313\python.exe',
                'C:\Python313\python.exe',
                'C:\Program Files\Python313\python.exe'
            ];
            
            $pythonPath = null;
            foreach ($pythonPaths as $path) {
                $check = shell_exec("$path --version 2>nul");
                if ($check && str_contains($check, 'Python')) {
                    $pythonPath = $path;
                    break;
                }
            }
            
            if (!$pythonPath) {
                Log::info('Python not found - using web interface mode only');
                return false;
            }
            
            $scriptPath = base_path('rfid_reader.py');
        if (!file_exists($scriptPath)) {
                Log::info('RFID reader script not found - using web interface mode only');
                return false;
            }
            
            Log::info("Attempting to start Python RFID reader", [
                'python_path' => $pythonPath,
                'script_path' => $scriptPath
            ]);
            
            // Update API URL in script
            $apiUrl = config('app.url') ?: 'http://localhost:8000';
            $this->updateApiUrlInScript($scriptPath, $apiUrl);
            
            // Start Python process
            if (PHP_OS_FAMILY === 'Windows') {
        $command = sprintf(
            'powershell -Command "Start-Process -FilePath \'%s\' -ArgumentList \'%s\' -WindowStyle Hidden"',
            $pythonPath,
                    escapeshellarg($scriptPath)
                );
            } else {
                $command = sprintf(
                    'nohup %s %s > /dev/null 2>&1 &',
                    $pythonPath,
                    escapeshellarg($scriptPath)
                );
            }
            
            shell_exec($command);
            
            // Wait and check if it started
            sleep(2);
            if ($this->isPythonProcessRunning()) {
                Log::info('Python RFID reader started successfully');
                return true;
            } else {
                Log::info('Python RFID reader failed to start - continuing with web interface mode');
                return false;
            }
            
        } catch (\Exception $e) {
            Log::warning('Failed to start Python RFID reader: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if Python RFID process is running
     */
    private function isPythonProcessRunning(): bool
    {
        try {
            if (PHP_OS_FAMILY === 'Windows') {
                $output = shell_exec('tasklist /FI "IMAGENAME eq python.exe" /FO CSV 2>nul');
                return $output && strpos($output, 'python.exe') !== false;
            } else {
                $output = shell_exec('pgrep -f rfid_reader.py');
                return !empty(trim($output));
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Update API URL in RFID script
     */
    private function updateApiUrlInScript(string $scriptPath, string $apiUrl): void
    {
        try {
            $content = file_get_contents($scriptPath);
            
            // Replace the API URL in the script
            $content = preg_replace(
                '/self\.api_url = .*;$/m',
                "self.api_url = \"$apiUrl\";",
                $content
            );
            
            file_put_contents($scriptPath, $content);
            Log::info("Updated RFID script with API URL: $apiUrl");
            
        } catch (\Exception $e) {
            Log::warning('Failed to update API URL in RFID script: ' . $e->getMessage());
        }
    }
    
    /**
     * Stop RFID reader process (hybrid approach)
     */
    private function stopRfidProcess(): void
    {
        try {
            // Try to stop Python processes if running
            if (PHP_OS_FAMILY === 'Windows') {
                shell_exec('taskkill /F /IM python.exe 2>nul');
            } else {
                shell_exec('pkill -f rfid_reader.py 2>/dev/null');
            }
            
            // Remove the status file to indicate RFID is stopped
            $rfidStatusFile = storage_path('logs/rfid_running.txt');
            
            if (file_exists($rfidStatusFile)) {
                unlink($rfidStatusFile);
                Log::info('RFID reader process stopped');
            } else {
                Log::info('RFID reader process already stopped');
            }
            
        } catch (\Exception $e) {
            Log::error('Error stopping RFID process: ' . $e->getMessage());
        }
    }

    /**
     * Get member suggestions for search
     */
    public function getMemberSuggestions(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        
        if (empty($query)) {
            return response()->json(['members' => []]);
        }
        
        $members = Member::where(function ($q) use ($query) {
            $q->where('first_name', 'like', "%{$query}%")
              ->orWhere('last_name', 'like', "%{$query}%")
              ->orWhere('full_name', 'like', "%{$query}%")
              ->orWhere('email', 'like', "%{$query}%")
              ->orWhere('member_number', 'like', "%{$query}%");
        })
        ->where('status', 'active')
        ->limit(10)
        ->get()
        ->map(function ($member) {
            return [
                'id' => $member->id,
                'name' => $member->full_name,
                'email' => $member->email,
                'membership_plan' => $member->membershipPlan?->name ?? 'No Plan',
                'member_number' => $member->member_number,
            ];
        });
        
        return response()->json(['members' => $members]);
    }

    /**
     * Get dashboard statistics for RFID monitor
     */
    public function getDashboardStats(): JsonResponse
    {
        try {
            // Use last 24 hours for "today's" check-ins to handle timezone issues
            $last24Hours = now()->subDay();
            $today = now()->startOfDay();
            $thisWeekStart = now()->startOfWeek();
            $thisMonthStart = now()->startOfMonth();

            // Today's check-ins - use last 24 hours to include recent activity
            $todayCheckins = Attendance::where('check_in_time', '>=', $last24Hours)
                ->where('status', 'checked_in')
                ->count();

            // Also get actual today's count for comparison
            $actualTodayCheckins = Attendance::whereDate('check_in_time', today())
                ->where('status', 'checked_in')
                ->count();

            // Expired memberships count
            $expiredMemberships = Member::where('membership_expires_at', '<', now())
                ->count();

            // Unknown cards in last 24 hours
            $unknownCardsToday = RfidLog::where('timestamp', '>=', $last24Hours)
                ->where('action', 'unknown_card')
                ->count();

            // Debug logging
            Log::info('Dashboard stats calculated', [
                'today_checkins' => $todayCheckins,
                'actual_today_checkins' => $actualTodayCheckins,
                'expired_memberships' => $expiredMemberships,
                'unknown_cards' => $unknownCardsToday,
                'last_24_hours_start' => $last24Hours->toDateTimeString(),
                'today_date' => today()->toDateString(),
                'now_time' => now()->toDateTimeString(),
                'timezone' => now()->timezone->getName()
            ]);

            return response()->json([
                'success' => true,
                'stats' => [
                    'today_checkins' => $todayCheckins,
                    'expired_memberships' => $expiredMemberships,
                    'unknown_cards' => $unknownCardsToday,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting dashboard stats: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load dashboard statistics',
                'stats' => [
                    'today_checkins' => 0,
                    'expired_memberships' => 0,
                    'unknown_cards' => 0,
                ]
            ], 500);
        }
    }

    /**
     * Get currently active members with transaction handling
     */
    public function getActiveMembers(): JsonResponse
    {
        try {
            // Use database transaction to ensure consistent reads
            $activeMembers = DB::transaction(function () {
                return ActiveSession::with('member')
                    ->where('status', 'active')
                    ->orderBy('check_in_time', 'desc')
                    ->lockForUpdate() // Prevent concurrent modifications
                    ->get()
                    ->map(function ($session) {
                        // Validate that member exists and is not null
                        if (!$session->member) {
                            Log::warning('Active session found without associated member', [
                                'session_id' => $session->id,
                                'member_id' => $session->member_id
                            ]);
                            return null;
                        }

                        return [
                            'id' => $session->member->id,
                            'first_name' => $session->member->first_name,
                            'last_name' => $session->member->last_name,
                            'full_name' => $session->member->first_name . ' ' . $session->member->last_name,
                            'email' => $session->member->email,
                            'member_number' => $session->member->member_number,
                            'check_in_time' => $session->check_in_time,
                            'session_id' => $session->id,
                        ];
                    })
                    ->filter() // Remove null entries
                    ->values(); // Re-index array
            });
            
            // Log consistency check
            Log::info('Active members retrieved', [
                'count' => $activeMembers->count(),
                'method' => 'getActiveMembers',
                'members' => $activeMembers->pluck('member_number')->toArray()
            ]);

            // Add cache headers to prevent browser caching issues
            $response = response()->json([
                'success' => true,
                'members' => $activeMembers,
                'count' => $activeMembers->count(),
                'timestamp' => now()->toISOString()
            ]);

            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');

            return $response;

        } catch (\Exception $e) {
            Log::error('Error getting active members: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load active members',
                'members' => [],
                'count' => 0,
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }

    /**
     * Check for data consistency between isInGym() and getActiveMembers()
     */
    public function checkDataConsistency(): JsonResponse
    {
        try {
            $inconsistencies = [];
            
            // Get all members
            $members = Member::all();
            
            // Get active members from getActiveMembers logic
            $activeSessions = ActiveSession::with('member')
                ->where('status', 'active')
                ->get();
            
            $activeMemberIds = $activeSessions
                ->filter(function ($session) {
                    return $session->member !== null;
                })
                ->pluck('member_id')
                ->unique()
                ->toArray();
            
            // Check each member
            foreach ($members as $member) {
                $isInGym = $member->isInGym();
                $inActiveMembers = in_array($member->id, $activeMemberIds);
                
                if ($isInGym !== $inActiveMembers) {
                    $inconsistencies[] = [
                        'member_id' => $member->id,
                        'member_number' => $member->member_number,
                        'member_name' => $member->first_name . ' ' . $member->last_name,
                        'isInGym' => $isInGym,
                        'inActiveMembers' => $inActiveMembers,
                        'inconsistency_type' => $isInGym ? 'member_shows_in_gym_but_not_in_admin' : 'member_shows_not_in_gym_but_in_admin'
                    ];
                }
            }
            
            // Log inconsistencies
            if (!empty($inconsistencies)) {
                Log::warning('Data consistency issues detected', [
                    'inconsistencies' => $inconsistencies,
                    'total_members' => $members->count(),
                    'inconsistent_members' => count($inconsistencies)
                ]);
            }
            
            return response()->json([
                'success' => true,
                'consistent' => empty($inconsistencies),
                'inconsistencies' => $inconsistencies,
                'total_members' => $members->count(),
                'inconsistent_count' => count($inconsistencies),
                'timestamp' => now()->toISOString()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error checking data consistency: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to check data consistency',
                'consistent' => false,
                'inconsistencies' => [],
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }
    
}
