<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Payment;
use App\Models\MembershipPlan;

class EmployeeController extends Controller
{
    public function dashboard()
    {
        // Use the unified dashboard controller with employee role filtering
        $dashboardController = new \App\Http\Controllers\DashboardController();
        return $dashboardController->index();
    }

    public function rfidMonitor()
    {
        return view('employee.rfid-monitor');
    }

    public function members(Request $request)
    {
        // Get search and filter parameters
        $search = $request->get('search');
        $selectedMembership = $request->get('membership');
        
        // Build the query
        $query = \App\Models\Member::with(['currentMembershipPeriod', 'payments']);
        
        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('uid', 'like', "%{$search}%");
            });
        }
        
        // Apply membership filter
        if ($selectedMembership) {
            $query->whereHas('currentMembershipPeriod', function($q) use ($selectedMembership) {
                $q->where('plan_type', $selectedMembership);
            })->orWhereHas('payments', function($q) use ($selectedMembership) {
                $q->where('plan_type', $selectedMembership)
                  ->where('status', 'completed');
            });
        }
        
        // Get paginated results
        $members = $query->paginate(15);
        
        return view('employee.members.index', compact('members', 'search', 'selectedMembership'));
    }

    public function createMember()
    {
        return view('employee.members.create');
    }

    public function payments(Request $request)
    {
        // Get search and filter parameters
        $search = $request->get('search');
        $planType = $request->get('plan_type');
        $date = $request->get('date');
        $status = $request->get('status');
        
        // Build the query
        $query = Payment::with('member');
        
        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('member', function($memberQuery) use ($search) {
                      $memberQuery->where('first_name', 'like', "%{$search}%")
                                  ->orWhere('last_name', 'like', "%{$search}%")
                                  ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        // Apply plan type filter
        if ($planType) {
            $query->where('plan_type', $planType);
        }
        
        // Apply date filter
        if ($date) {
            $query->whereDate('created_at', $date);
        }
        
        // Apply status filter
        if ($status) {
            $query->where('status', $status);
        }
        
        // Get paginated results
        $payments = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Get summary statistics for all completed payments (not just current page)
        $completedPayments = Payment::where('status', 'completed');
        
        // Apply same filters to summary statistics
        if ($search) {
            $completedPayments->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('member', function($memberQuery) use ($search) {
                      $memberQuery->where('first_name', 'like', "%{$search}%")
                                  ->orWhere('last_name', 'like', "%{$search}%")
                                  ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($planType) {
            $completedPayments->where('plan_type', $planType);
        }
        
        if ($date) {
            $completedPayments->whereDate('created_at', $date);
        }
        
        if ($status) {
            $completedPayments->where('status', $status);
        }
        
        $completedCount = $completedPayments->count();
        $totalRevenue = $completedPayments->sum('amount');
        
        return view('employee.payments', compact('payments', 'completedCount', 'totalRevenue'));
    }

    // Employee Membership Methods
    public function manageMember()
    {
        $members = Member::with('currentMembershipPeriod')->get();
        $planTypes = config('membership.plan_types');
        $durationTypes = config('membership.duration_types');
        
        return view('membership.manage-member', compact('members', 'planTypes', 'durationTypes'));
    }

    public function processPayment(Request $request)
    {
        // Payment processing logic for employees
        return response()->json(['success' => true, 'message' => 'Payment processed successfully']);
    }

    public function plans()
    {
        $plans = MembershipPlan::orderBy('price')->get();
        $planTypes = config('membership.plan_types');
        $durationTypes = config('membership.duration_types');
        return view('employee.membership-plans', compact('plans', 'planTypes', 'durationTypes'));
    }

    public function paymentDetails($id)
    {
        $payment = Payment::with('member')->findOrFail($id);
        
        $html = view('membership.payments.details', compact('payment'))->render();
        
        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }

    public function exportPaymentsCsv()
    {
        // CSV export logic for employee payments
        return response()->download('employee-payments.csv');
    }

    public function printPayment($id)
    {
        $payment = Payment::with('member')->findOrFail($id);
        return view('membership.payments.receipt', compact('payment'));
    }

    // Employee Membership Plans API Methods
    public function getAllPlans()
    {
        $plans = MembershipPlan::orderBy('price')->get();
        return response()->json(['plans' => $plans]);
    }

    public function getPlanTypes()
    {
        $planTypes = config('membership.plan_types');
        return response()->json(['plan_types' => $planTypes]);
    }

    public function getDurationTypes()
    {
        $durationTypes = config('membership.duration_types');
        return response()->json(['duration_types' => $durationTypes]);
    }
}
