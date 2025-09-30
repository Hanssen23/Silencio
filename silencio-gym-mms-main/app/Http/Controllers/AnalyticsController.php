<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Payment;
use App\Models\Member;
use App\Models\RfidLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Get weekly attendance data for the past 7 days
     */
    public function weeklyAttendance(Request $request)
    {
        return $this->getWeeklyAttendance($request);
    }

    /**
     * Get weekly attendance data for the past 7 days (internal method)
     */
    public function getWeeklyAttendance(Request $request)
    {
        $days = $request->get('days', 7);
        $startDate = Carbon::now()->subDays($days - 1)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        // Get daily attendance counts (SQLite compatible)
        $attendanceData = Attendance::select(
                DB::raw('date(check_in_time) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->whereBetween('check_in_time', [$startDate, $endDate])
            ->groupBy(DB::raw('date(check_in_time)'))
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Generate complete date range with zero counts for missing days
        $result = [];
        for ($i = 0; $i < $days; $i++) {
            $date = $startDate->copy()->addDays($i);
            $dateKey = $date->format('Y-m-d');
            
            $result[] = [
                'date' => $dateKey,
                'day' => $date->format('D'), // Mon, Tue, Wed, etc.
                'count' => $attendanceData->get($dateKey, (object)['count' => 0])->count ?? 0,
                'formatted_date' => $date->format('M j')
            ];
        }

        return response()->json([
            'data' => $result,
            'total' => array_sum(array_column($result, 'count')),
            'period' => [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d')
            ]
        ]);
    }

    /**
     * Get weekly revenue data for the past 7 days
     */
    public function weeklyRevenue(Request $request)
    {
        return $this->getWeeklyRevenue($request);
    }

    /**
     * Get weekly revenue data for the past 7 days (internal method)
     */
    public function getWeeklyRevenue(Request $request)
    {
        $days = $request->get('days', 7);
        $startDate = Carbon::now()->subDays($days - 1)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        // Get daily revenue (SQLite compatible)
        $revenueData = Payment::select(
                DB::raw('date(payment_date) as date'),
                DB::raw('SUM(amount) as total')
            )
            ->where('status', 'completed')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->groupBy(DB::raw('date(payment_date)'))
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Generate complete date range with zero revenue for missing days
        $result = [];
        for ($i = 0; $i < $days; $i++) {
            $date = $startDate->copy()->addDays($i);
            $dateKey = $date->format('Y-m-d');
            
            $result[] = [
                'date' => $dateKey,
                'day' => $date->format('D'), // Mon, Tue, Wed, etc.
                'revenue' => $revenueData->get($dateKey, (object)['total' => 0])->total ?? 0,
                'formatted_date' => $date->format('M j')
            ];
        }

        return response()->json([
            'data' => $result,
            'total' => array_sum(array_column($result, 'revenue')),
            'period' => [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d')
            ]
        ]);
    }

    /**
     * Get monthly revenue data for the current month
     */
    public function monthlyRevenue(Request $request)
    {
        return $this->getMonthlyRevenue($request);
    }

    /**
     * Get monthly revenue data for the current month (internal method)
     */
    public function getMonthlyRevenue(Request $request)
    {
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);
        
        $startDate = Carbon::create($year, $month, 1)->startOfDay();
        $endDate = $startDate->copy()->endOfMonth();

        // Get daily revenue (SQLite compatible)
        $revenueData = Payment::select(
                DB::raw('date(payment_date) as date'),
                DB::raw('SUM(amount) as total')
            )
            ->where('status', 'completed')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->groupBy(DB::raw('date(payment_date)'))
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Generate complete month with zero revenue for missing days
        $result = [];
        $currentDate = $startDate->copy();
        
        while ($currentDate->lte($endDate)) {
            $dateKey = $currentDate->format('Y-m-d');
            
            $result[] = [
                'date' => $dateKey,
                'day' => $currentDate->format('j'),
                'revenue' => $revenueData->get($dateKey, (object)['total' => 0])->total ?? 0,
                'formatted_date' => $currentDate->format('M j')
            ];
            
            $currentDate->addDay();
        }

        return response()->json([
            'data' => $result,
            'total' => array_sum(array_column($result, 'revenue')),
            'period' => [
                'month' => $month,
                'year' => $year,
                'month_name' => $startDate->format('F')
            ]
        ]);
    }

    /**
     * Get real-time dashboard statistics
     */
    public function getDashboardStats()
    {
        $now = Carbon::now();
        
        // Current active members (in gym)
        $currentActiveMembers = Member::whereHas('activeSessions', function($query) {
            $query->where('status', 'active')
                  ->whereNull('check_out_time');
        })->count();

        // Today's attendance
        $todayAttendance = Attendance::whereDate('check_in_time', $now->toDateString())->count();

        // This week's attendance
        $thisWeekAttendance = Attendance::whereBetween('check_in_time', [
            $now->startOfWeek(),
            $now->endOfWeek()
        ])->count();

        // This week's revenue
        $thisWeekRevenue = Payment::where('status', 'completed')
            ->whereBetween('payment_date', [
                $now->startOfWeek(),
                $now->endOfWeek()
            ])
            ->sum('amount');

        // Pending payments
        $pendingPayments = Payment::where('status', 'pending')->count();

        // Expiring memberships this week
        $expiringMembershipsThisWeek = Member::expiringThisWeek()->count();

        // Total active members
        $totalActiveMembers = Member::active()->count();

        // Recent RFID activity (last 10 logs)
        $recentRfidLogs = RfidLog::latest('timestamp')
            ->take(10)
            ->get()
            ->map(function($log) {
                return [
                    'id' => $log->id,
                    'uid' => $log->uid,
                    'timestamp' => $log->timestamp,
                    'status' => $log->status,
                    'member_name' => $log->member ? $log->member->full_name : 'Unknown',
                    'formatted_time' => Carbon::parse($log->timestamp)->format('H:i:s')
                ];
            });

        return response()->json([
            'current_active_members' => $currentActiveMembers,
            'today_attendance' => $todayAttendance,
            'this_week_attendance' => $thisWeekAttendance,
            'this_week_revenue' => $thisWeekRevenue,
            'pending_payments' => $pendingPayments,
            'expiring_memberships_this_week' => $expiringMembershipsThisWeek,
            'total_active_members' => $totalActiveMembers,
            'recent_rfid_logs' => $recentRfidLogs,
            'last_updated' => $now->format('H:i:s'),
            'timestamp' => $now->toISOString()
        ]);
    }

    /**
     * Get attendance trends for different periods
     */
    public function getAttendanceTrends(Request $request)
    {
        $period = $request->get('period', 'week'); // week, month, year
        
        switch ($period) {
            case 'week':
                return $this->getWeeklyAttendance($request);
                
            case 'month':
                $startDate = Carbon::now()->subMonths(12)->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                
                $data = Attendance::select(
                        DB::raw("strftime('%Y', check_in_time) as year"),
                        DB::raw("strftime('%m', check_in_time) as month"),
                        DB::raw('COUNT(*) as count')
                    )
                    ->whereBetween('check_in_time', [$startDate, $endDate])
                    ->groupBy(DB::raw("strftime('%Y', check_in_time), strftime('%m', check_in_time)"))
                    ->orderBy('year')
                    ->orderBy('month')
                    ->get();
                
                $result = [];
                foreach ($data as $item) {
                    $date = Carbon::create($item->year, $item->month, 1);
                    $result[] = [
                        'date' => $date->format('Y-m'),
                        'month' => $date->format('M'),
                        'count' => $item->count,
                        'formatted_date' => $date->format('M Y')
                    ];
                }
                
                return response()->json([
                    'data' => $result,
                    'period' => 'monthly',
                    'total' => array_sum(array_column($result, 'count'))
                ]);
                
            default:
                return response()->json(['error' => 'Invalid period'], 400);
        }
    }

    /**
     * Get revenue trends for different periods
     */
    public function getRevenueTrends(Request $request)
    {
        $period = $request->get('period', 'month'); // month, year
        
        switch ($period) {
            case 'month':
                return $this->getMonthlyRevenue($request);
                
            case 'year':
                $year = $request->get('year', Carbon::now()->year);
                $startDate = Carbon::create($year, 1, 1)->startOfDay();
                $endDate = Carbon::create($year, 12, 31)->endOfDay();
                
                $data = Payment::select(
                        DB::raw("CAST(strftime('%m', payment_date) AS INTEGER) as month"),
                        DB::raw('SUM(amount) as total')
                    )
                    ->where('status', 'completed')
                    ->whereBetween('payment_date', [$startDate, $endDate])
                    ->groupBy(DB::raw("strftime('%m', payment_date)"))
                    ->orderBy('month')
                    ->get();
                
                $result = [];
                for ($i = 1; $i <= 12; $i++) {
                    $monthData = $data->where('month', $i)->first();
                    $date = Carbon::create($year, $i, 1);
                    $result[] = [
                        'month' => $i,
                        'month_name' => $date->format('M'),
                        'revenue' => $monthData ? $monthData->total : 0,
                        'formatted_date' => $date->format('M Y')
                    ];
                }
                
                return response()->json([
                    'data' => $result,
                    'period' => 'yearly',
                    'total' => array_sum(array_column($result, 'revenue')),
                    'year' => $year
                ]);
                
            default:
                return response()->json(['error' => 'Invalid period'], 400);
        }
    }

    /**
     * Get real-time RFID activity feed
     */
    public function getRfidActivity(Request $request)
    {
        $limit = $request->get('limit', 20);
        
        $logs = RfidLog::with('member')
            ->latest('timestamp')
            ->take($limit)
            ->get()
            ->map(function($log) {
                return [
                    'id' => $log->id,
                    'uid' => $log->uid,
                    'timestamp' => $log->timestamp,
                    'status' => $log->status,
                    'member_name' => $log->member ? $log->member->full_name : 'Unknown',
                    'member_id' => $log->member_id,
                    'formatted_time' => Carbon::parse($log->timestamp)->format('H:i:s'),
                    'formatted_date' => Carbon::parse($log->timestamp)->format('M j, Y'),
                    'is_success' => $log->status === 'success',
                    'is_unknown' => $log->status === 'unknown_card'
                ];
            });

        return response()->json([
            'logs' => $logs,
            'count' => $logs->count(),
            'last_updated' => Carbon::now()->format('H:i:s')
        ]);
    }
}
