<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ActiveSession;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AutoTapOutMembers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rfid:auto-tapout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically tap out all active members at midnight (gym closing time)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🕛 Starting automatic tap-out process...');
        
        // Get all active sessions
        $activeSessions = ActiveSession::with('member', 'attendance')
            ->where('status', 'active')
            ->whereNull('check_out_time')
            ->get();

        if ($activeSessions->isEmpty()) {
            $this->info('✅ No active members found. Nothing to tap out.');
            return 0;
        }

        $this->info("📊 Found {$activeSessions->count()} active members to tap out.");

        $tappedOutCount = 0;
        $errors = [];

        foreach ($activeSessions as $session) {
            try {
                $this->tapOutMember($session);
                $tappedOutCount++;
                
                $this->info("✅ Tapped out: {$session->member->first_name} {$session->member->last_name} (UID: {$session->member->uid})");
                
            } catch (\Exception $e) {
                $error = "❌ Failed to tap out {$session->member->first_name} {$session->member->last_name}: " . $e->getMessage();
                $errors[] = $error;
                $this->error($error);
                
                Log::error('Auto tap-out failed', [
                    'member_id' => $session->member_id,
                    'session_id' => $session->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Summary
        $this->info("\n📋 Auto tap-out summary:");
        $this->info("✅ Successfully tapped out: {$tappedOutCount} members");
        
        if (!empty($errors)) {
            $this->error("❌ Failed to tap out: " . count($errors) . " members");
            foreach ($errors as $error) {
                $this->error("   {$error}");
            }
        }

        // Log the operation
        Log::info('Auto tap-out completed', [
            'total_active' => $activeSessions->count(),
            'successful' => $tappedOutCount,
            'failed' => count($errors),
            'timestamp' => now()
        ]);

        return 0;
    }

    /**
     * Tap out a specific member
     */
    private function tapOutMember(ActiveSession $session)
    {
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

        // Log the automatic tap-out
        Log::info('Member automatically tapped out', [
            'member_id' => $session->member_id,
            'member_name' => $session->member->first_name . ' ' . $session->member->last_name,
            'member_uid' => $session->member->uid,
            'check_in_time' => $session->check_in_time,
            'check_out_time' => $now,
            'session_duration' => $session->session_duration,
            'reason' => 'automatic_midnight_tapout'
        ]);
    }
}