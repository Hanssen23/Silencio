<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MembershipPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $selectedMembership = $request->query('membership');
        $search = $request->query('search');

        $membersQuery = Member::query()
            ->with([
                'currentMembershipPeriod',
                'attendances' => function ($query) {
                    $query->latest()->limit(1);
                },
            ]);

        // Filter by membership type if provided and valid
        if (in_array($selectedMembership, ['basic', 'premium', 'vip'])) {
            $membersQuery->whereHas('currentMembershipPeriod', function ($query) use ($selectedMembership) {
                $query->where('plan_type', $selectedMembership)
                      ->where('status', 'active')
                      ->where('expiration_date', '>', now());
            });
        }

        // Search filter (name, email, member_number, uid, mobile)
        if (!empty($search)) {
            $membersQuery->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('member_number', 'like', "%{$search}%")
                    ->orWhere('uid', 'like', "%{$search}%")
                    ->orWhere('mobile_number', 'like', "%{$search}%");
            });
        }

        $members = $membersQuery
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();
        
        // Check if this is an employee request
        if (request()->is('employee/*')) {
            return view('employee.members', compact('members', 'selectedMembership', 'search'));
        }
        
        return view('members.list', compact('members', 'selectedMembership', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check if this is an employee request
        if (request()->is('employee/*')) {
            return view('employee.members.create');
        }
        
        return view('members.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:20',
            'email' => 'required|email|unique:members,email|unique:users,email',
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'nullable|in:member,admin,employee',
        ], [
            'email.unique' => 'This email is already registered',
        ]);

        // Restrict role creation based on user type
        $requestedRole = $request->role ?? 'member';
        if (request()->is('employee/*')) {
            // Employees can only create member accounts
            $requestedRole = 'member';
        } elseif (auth()->user()->role !== 'admin') {
            // Only admins can create admin/employee accounts
            $requestedRole = 'member';
        }

        // Get an available UID from the pool
        $availableUid = Member::getAvailableUid();
        
        if (!$availableUid) {
            $redirectRoute = request()->is('employee/*') ? 'employee.members' : 'members.index';
            return redirect()->route($redirectRoute)
                ->with('error', 'No UIDs available in the pool. Please contact administrator.');
        }

        // Clean mobile number (remove spaces and add +63 prefix)
        $mobileNumber = '+63' . preg_replace('/\D/', '', $request->mobile_number);

        $memberData = [
            'uid' => $availableUid,
            'member_number' => Member::generateMemberNumber(),
            'membership' => null, // No plan assigned initially
            'subscription_status' => 'not_subscribed',
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'mobile_number' => $mobileNumber,
            'email' => $request->email,
            'status' => 'active',
            'role' => $requestedRole, // Use the restricted role
        ];

        // Add password if provided (for admin-created accounts)
        if ($request->password) {
            $memberData['password'] = Hash::make($request->password);
        }

        try {
            $member = Member::create($memberData);
        } catch (\Exception $e) {
            // If member creation fails, return the UID to the pool
            Member::returnUidToPool($availableUid);
            throw $e; // Re-throw the exception
        }

        $redirectRoute = request()->is('employee/*') ? 'employee.members' : 'members.index';
        return redirect()->route($redirectRoute)
            ->with('success', 'Member created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $member = Member::findOrFail($id);
        
        // Check if this is an employee request
        if (request()->is('employee/*')) {
            return view('employee.members.show', compact('member'));
        }
        
        return view('members.show', compact('member'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $member = Member::findOrFail($id);
        
        // Check if this is an employee request
        if (request()->is('employee/*')) {
            return view('employee.members.edit', compact('member'));
        }
        
        return view('members.edit', compact('member'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $member = Member::findOrFail($id);

        // Check if this is an employee request
        if (request()->is('employee/*')) {
            // Employee can only edit name fields
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
            ]);

            $member->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
            ]);
        } else {
            // Admin can edit all fields
            $request->validate([
                'uid' => 'required|string|unique:members,uid,' . $id,
                'member_number' => 'required|string|unique:members,member_number,' . $id,
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:members,email,' . $id . '|unique:users,email',
                'mobile_number' => 'nullable|string|max:20',
                // membership is not validated as it's automatically set during payment processing
            ], [
                'email.unique' => 'This email is already registered',
            ]);

            $member->update([
                'uid' => $request->uid,
                'member_number' => $request->member_number,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'mobile_number' => $request->mobile_number,
                // membership is not updated as it's automatically set during payment processing
            ]);
        }

        $redirectRoute = request()->is('employee/*') ? 'employee.members' : 'members.index';
        return redirect()->route($redirectRoute)
            ->with('success', 'Member updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $member = Member::findOrFail($id);
        
        // Return the UID to the pool before deleting the member
        if ($member->uid) {
            Member::returnUidToPool($member->uid);
        }
        
        $member->delete();

        $redirectRoute = request()->is('employee/*') ? 'employee.members' : 'members.index';
        return redirect()->route($redirectRoute)
            ->with('success', 'Member deleted successfully!');
    }

    /**
     * Display member profile with membership history
     */
    public function profile(string $id, Request $request)
    {
        $member = Member::with([
            'currentMembershipPeriod',
            'membershipPeriods.payment',
            'activeSessions' => function($query) {
                $query->where('status', 'active');
            }
        ])->findOrFail($id);
        
        // Get all membership periods (current and historical) ordered by purchase date
        $membershipPeriods = $member->membershipPeriods()
            ->with('payment')
            ->orderBy('created_at', 'desc') // Order by purchase date (created_at)
            ->get();

        // Get current active membership
        $currentMembership = $member->currentMembershipPeriod;

        // Get paginated attendance records (5 per page)
        $attendances = $member->attendances()
            ->orderBy('check_in_time', 'desc')
            ->paginate(5)
            ->withQueryString();

        // Get paginated RFID activity (5 per page)
        $rfidLogs = \App\Models\RfidLog::where('card_uid', $member->uid)
            ->orderBy('timestamp', 'desc')
            ->paginate(5)
            ->withQueryString();

        // Get paginated payment history (5 per page)
        $payments = $member->payments()
            ->orderBy('payment_date', 'desc')
            ->paginate(5)
            ->withQueryString();

        // Check if this is an employee request
        if (request()->is('employee/*')) {
            return view('employee.members.profile', compact(
                'member', 
                'membershipPeriods', 
                'currentMembership', 
                'payments',
                'attendances',
                'rfidLogs'
            ));
        }
        
        return view('members.profile', compact(
            'member', 
            'membershipPeriods', 
            'currentMembership', 
            'payments',
            'attendances',
            'rfidLogs'
        ));
    }

    /**
     * Display membership history for a member
     */
    public function membershipHistory(string $id)
    {
        $member = Member::findOrFail($id);
        
        $membershipPeriods = $member->membershipPeriods()
            ->with('payment')
            ->orderBy('start_date', 'desc')
            ->paginate(10);

        return view('employee.members.membership-history', compact('member', 'membershipPeriods'));
    }

    /**
     * Update member UID
     */
    public function updateUid(Request $request, string $id)
    {
        $member = Member::findOrFail($id);
        
        $request->validate([
            'uid' => 'required|string|unique:members,uid,' . $id,
        ]);

        $oldUid = $member->uid;
        $member->update(['uid' => $request->input('uid')]);

        return response()->json([
            'success' => true,
            'message' => "UID updated from {$oldUid} to {$request->input('uid')}",
            'member' => [
                'id' => $member->id,
                'name' => $member->first_name . ' ' . $member->last_name,
                'uid' => $member->uid,
            ]
        ]);
    }
}