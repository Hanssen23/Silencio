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
        return view('employee.dashboard');
    }

    public function rfidMonitor()
    {
        return view('employee.rfid-monitor');
    }

    public function members()
    {
        return view('employee.members.index');
    }

    public function payments()
    {
        return view('employee.payments.index');
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
        return view('membership.plans.index', compact('plans'));
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
