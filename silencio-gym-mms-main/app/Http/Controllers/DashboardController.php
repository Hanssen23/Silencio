<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\ActiveSession;
use App\Models\Attendance;
use App\Models\Payment;
use App\Models\RfidLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Cache key with current hour to refresh hourly
        $cacheKey = 'dashboard_data_' . Carbon::now()->format('Y-m-d_H');
        
        // Get dashboard data from cache or compute it
        $dashboardData = Cache::remember($cacheKey, 3600, function () {
            // Get all counts in a single query where possible (SQLite compatible)
            $counts = DB::select("
                SELECT 
                    (SELECT COUNT(*) FROM active_sessions WHERE status = 'active') as active_sessions_count,
                    (SELECT COUNT(*) FROM members WHERE status = 'active') as active_members_count,
                    (SELECT COUNT(*) FROM attendances WHERE date(check_in_time) = date('now')) as today_attendance_count,
                    (SELECT COUNT(*) FROM attendances WHERE check_in_time >= date('now', 'weekday 0', '-7 days')) as week_attendance_count,
                    (SELECT COUNT(*) FROM payments WHERE status = 'pending') as pending_payments_count,
                    (SELECT COUNT(*) FROM members WHERE status = 'active' AND membership_expires_at <= date('now', '+7 days')) as expiring_memberships_count,
                    (SELECT COUNT(*) FROM members WHERE status = 'active' AND membership_expires_at < date('now')) as expired_memberships_count,
                    (SELECT COUNT(*) FROM rfid_logs WHERE date(timestamp) = date('now') AND card_uid NOT IN (SELECT uid FROM members)) as unknown_cards_count
            ")[0];

            // Get this week's revenue with proper formatting
            $thisWeekRevenue = Payment::completed()
                ->thisWeek()
                ->sum('amount');

            // Get recent RFID logs with member info in a single query
            $recentRfidLogs = RfidLog::select('rfid_logs.*', 'members.first_name', 'members.last_name')
                ->leftJoin('members', 'rfid_logs.card_uid', '=', 'members.uid')
                ->latest('timestamp')
                ->take(10)
                ->get();

            // Get active members with their membership info
            $members = Member::select('members.*', 'membership_periods.plan_type', 'membership_periods.expiration_date')
                ->join('membership_periods', 'members.current_membership_period_id', '=', 'membership_periods.id')
                ->where('members.status', 'active')
                ->whereNotNull('members.membership_expires_at')
                ->get();

            return [
                'currentActiveMembersCount' => $counts->active_sessions_count,
                'totalActiveMembersCount' => $counts->active_members_count,
                'todayAttendance' => $counts->today_attendance_count,
                'thisWeekAttendance' => $counts->week_attendance_count,
                'thisWeekRevenue' => $thisWeekRevenue,
                'pendingPaymentsCount' => $counts->pending_payments_count,
                'expiringMembershipsCount' => $counts->expiring_memberships_count,
                'expiredMembershipsToday' => $counts->expired_memberships_count,
                'unknownCardsToday' => $counts->unknown_cards_count,
                'recentRfidLogs' => $recentRfidLogs,
                'members' => $members
            ];
        });

        return view('dashboard', $dashboardData);
    }

    /**
     * Get dashboard statistics for API calls
     */
    public function getStats()
    {
        // Cache key with current hour to refresh hourly
        $cacheKey = 'dashboard_stats_' . Carbon::now()->format('Y-m-d_H');

        // Get dashboard stats from cache or compute it
        $stats = Cache::remember($cacheKey, 3600, function () {
            // Get all counts in a single query where possible (SQLite compatible)
            $counts = DB::select("
                SELECT
                    (SELECT COUNT(*) FROM active_sessions WHERE status = 'active') as active_sessions_count,
                    (SELECT COUNT(*) FROM members WHERE status = 'active') as active_members_count,
                    (SELECT COUNT(*) FROM attendances WHERE date(check_in_time) = date('now')) as today_attendance_count,
                    (SELECT COUNT(*) FROM attendances WHERE check_in_time >= date('now', 'weekday 0', '-7 days')) as week_attendance_count,
                    (SELECT COUNT(*) FROM payments WHERE status = 'pending') as pending_payments_count,
                    (SELECT COUNT(*) FROM members WHERE status = 'active' AND membership_expires_at <= date('now', '+7 days')) as expiring_memberships_count,
                    (SELECT COUNT(*) FROM members WHERE status = 'active' AND membership_expires_at < date('now')) as expired_memberships_count,
                    (SELECT COUNT(*) FROM rfid_logs WHERE date(timestamp) = date('now') AND card_uid NOT IN (SELECT uid FROM members)) as unknown_cards_count
            ")[0];

            // Get this week's revenue
            $thisWeekRevenue = Payment::completed()
                ->thisWeek()
                ->sum('amount');

            return [
                'current_active_members' => $counts->active_sessions_count,
                'total_active_members' => $counts->active_members_count,
                'today_attendance' => $counts->today_attendance_count,
                'this_week_attendance' => $counts->week_attendance_count,
                'this_week_revenue' => $thisWeekRevenue,
                'pending_payments' => $counts->pending_payments_count,
                'expiring_memberships' => $counts->expiring_memberships_count,
                'expired_memberships_today' => $counts->expired_memberships_count,
                'unknown_cards_today' => $counts->unknown_cards_count
            ];
        });

        return response()->json($stats);
    }
}