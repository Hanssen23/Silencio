<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Carbon\Carbon;

class AccountController extends Controller
{
    /**
     * Display a listing of accounts
     */
    public function index(Request $request)
    {
        $currentUser = auth()->user();
        
        // Check if this is an employee request
        if (request()->is('employee/*')) {
            // For employees, only show their own account
            $accounts = collect([$currentUser]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'accounts' => $accounts->toArray(),
                    'pagination' => [
                        'current_page' => 1,
                        'last_page' => 1,
                        'per_page' => 1,
                        'total' => 1,
                    ]
                ]);
            }
            
            return view('employee.accounts', compact('accounts'));
        }
        
        // Admin functionality - show only admin and employee accounts (not members)
        $query = User::query()->whereIn('role', ['admin', 'employee']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role (only admin and employee roles)
        if ($request->has('role') && $request->role) {
            if (in_array($request->role, ['admin', 'employee'])) {
                $query->where('role', $request->role);
            }
        }

        // Filter by status (active/inactive based on email_verified_at)
        if ($request->has('status') && $request->status) {
            if ($request->status === 'active') {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        }

        $accounts = $query->orderBy('created_at', 'desc')->paginate(10);

        if ($request->expectsJson()) {
            return response()->json([
                'accounts' => $accounts->items(),
                'pagination' => [
                    'current_page' => $accounts->currentPage(),
                    'last_page' => $accounts->lastPage(),
                    'per_page' => $accounts->perPage(),
                    'total' => $accounts->total(),
                ]
            ]);
        }

        return view('accounts', compact('accounts'));
    }

    /**
     * Show the form for creating a new account
     */
    public function create(Request $request)
    {
        // Check if this is an employee request
        if (request()->is('employee/*')) {
            // Employees cannot create accounts
            return redirect()->route('employee.accounts.index')->with('error', 'Employees cannot create new accounts');
        }
        
        // For admins, show the create form
        return view('accounts.create');
    }

    /**
     * Store a newly created account
     */
    public function store(Request $request)
    {
        // Check if this is an employee request
        if (request()->is('employee/*')) {
            // Employees cannot create accounts
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employees cannot create accounts'
                ], 403);
            }
            return redirect()->route('employee.accounts.index')->with('error', 'Employees cannot create accounts');
        }
        
        // Only admins can create accounts
        $currentUser = auth()->user();
        if (!$currentUser || $currentUser->role !== 'admin') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only admins can create accounts'
                ], 403);
            }
            return back()->with('error', 'Only admins can create accounts');
        }
        
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email|unique:members,email',
            'mobile_number' => 'nullable|string|regex:/^9\d{2}\s\d{3}\s\d{4}$/',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,employee',
        ], [
            'first_name.required' => 'First name is required',
            'last_name.required' => 'Last name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email is already registered',
            'mobile_number.regex' => 'Please enter a valid 10-digit Philippine mobile number (e.g., 912 345 6789)',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters for admin accounts',
            'password.confirmed' => 'Password confirmation does not match',
            'role.required' => 'User type is required',
            'role.in' => 'Invalid user type selected',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Format mobile number if provided
            $mobileNumber = null;
            if ($request->mobile_number) {
                $mobileNumber = '+63' . preg_replace('/\D/', '', $request->mobile_number);
            }

            $user = User::create([
                'name' => $request->first_name . ' ' . $request->last_name, // Keep name for compatibility
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'mobile_number' => $mobileNumber,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'email_verified_at' => null, // Start as inactive
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Account created successfully',
                    'user' => $user
                ]);
            }

            return redirect()->route('accounts')->with('success', 'Account created successfully');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create account: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Failed to create account');
        }
    }

    /**
     * Show the form for editing an account
     */
    public function edit(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Check if this is an employee request
        if (request()->is('employee/*')) {
            // Employees can only edit their own account
            if ($user->id !== auth()->user()->id) {
                return redirect()->route('employee.accounts.index')->with('error', 'You can only edit your own account');
            }
            // Redirect to the main accounts page since the form is there
            return redirect()->route('employee.accounts.index');
        }
        
        // For admins, show the edit form
        return view('accounts.edit', compact('user'));
    }

    /**
     * Update the specified account
     */
    public function update(Request $request, $id)
    {
        $currentUser = auth()->user();
        $user = User::findOrFail($id);
        
        // Check if this is an employee request
        if (request()->is('employee/*')) {
            // Employees can only edit their own account
            if ($user->id !== $currentUser->id) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You can only edit your own account'
                    ], 403);
                }
                return back()->with('error', 'You can only edit your own account');
            }
        }
        
        \Log::info('Account update request received', [
            'user_id' => $user->id,
            'request_data' => $request->all(),
            'user_email' => $user->email
        ]);

        // Different validation rules for employees vs admins
        $validationRules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id . '|unique:members,email',
            'mobile_number' => 'nullable|string|regex:/^9\d{2}\s\d{3}\s\d{4}$/',
            'password' => 'nullable|string|min:8|confirmed',
        ];
        
        $validationMessages = [
            'first_name.required' => 'First name is required',
            'last_name.required' => 'Last name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email is already registered',
            'mobile_number.regex' => 'Please enter a valid 10-digit Philippine mobile number (e.g., 912 345 6789)',
            'password.min' => 'Password must be at least 8 characters for account management',
            'password.confirmed' => 'Password confirmation does not match',
        ];
        
        // Only admins can change roles
        if ($currentUser->role === 'admin') {
            $validationRules['role'] = 'required|in:admin,employee';
            $validationMessages['role.required'] = 'User type is required';
            $validationMessages['role.in'] = 'Invalid user type selected';
        }
        
        $validator = Validator::make($request->all(), $validationRules, $validationMessages);

        if ($validator->fails()) {
            \Log::warning('Account update validation failed', [
                'user_id' => $user->id,
                'errors' => $validator->errors()->toArray()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Format mobile number if provided
            $mobileNumber = null;
            if ($request->mobile_number) {
                $mobileNumber = '+63' . preg_replace('/\D/', '', $request->mobile_number);
            }

            $updateData = [
                'name' => $request->first_name . ' ' . $request->last_name,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'mobile_number' => $mobileNumber,
            ];
            
            // Only admins can change roles
            if ($currentUser->role === 'admin' && $request->has('role')) {
                $updateData['role'] = $request->role;
            }

            // Only update password if provided
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            \Log::info('Updating user with data', [
                'user_id' => $user->id,
                'update_data' => $updateData
            ]);

            $user->update($updateData);

            \Log::info('User updated successfully', [
                'user_id' => $user->id,
                'updated_user' => $user->toArray()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Account updated successfully',
                    'user' => $user
                ]);
            }

            // Redirect to appropriate route based on user type
            if (request()->is('employee/*')) {
                return redirect()->route('employee.accounts.index')->with('success', 'Account updated successfully');
            }
            
            return redirect()->route('accounts.index')->with('success', 'Account updated successfully');
        } catch (\Exception $e) {
            \Log::error('Account update failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update account: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Failed to update account');
        }
    }

    /**
     * Remove the specified account
     */
    public function destroy(Request $request, $id)
    {
        try {
            $currentUser = auth()->user();
            
            // Find the user to delete
            $user = User::findOrFail($id);
            
            // Check if this is an employee request
            if (request()->is('employee/*')) {
                // Employees cannot delete any accounts
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Employees cannot delete accounts'
                    ], 403);
                }
                return back()->with('error', 'Employees cannot delete accounts');
            }
            
            // Log the delete request for debugging
            \Log::info('Delete account request received', [
                'current_user_id' => $currentUser->id,
                'current_user_role' => $currentUser->role,
                'target_user_id' => $user->id,
                'target_user_role' => $user->role,
                'target_user_email' => $user->email,
                'request_expects_json' => $request->expectsJson(),
                'request_method' => $request->method()
            ]);
            
            // Prevent deletion of the current user
            if ($user->id === $currentUser->id) {
                \Log::warning('User tried to delete their own account', [
                    'user_id' => $currentUser->id
                ]);
                
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You cannot delete your own account'
                    ], 400);
                }
                return back()->with('error', 'You cannot delete your own account');
            }

            // Only allow admins to delete accounts
            if ($currentUser->role !== 'admin') {
                \Log::warning('Non-admin tried to delete account', [
                    'current_user_role' => $currentUser->role,
                    'target_user_id' => $user->id
                ]);
                
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Only admins can delete accounts'
                    ], 403);
                }
                return back()->with('error', 'Only admins can delete accounts');
            }

            \Log::info('Proceeding with account deletion', [
                'deleting_user_id' => $currentUser->id,
                'target_user_id' => $user->id
            ]);

            $user->delete();

            \Log::info('Account deleted successfully', [
                'deleted_user_id' => $user->id,
                'deleted_by' => $currentUser->id
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Account deleted successfully'
                ]);
            }

            return redirect()->route('accounts')->with('success', 'Account deleted successfully');
        } catch (\Exception $e) {
            \Log::error('Account deletion failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'current_user_id' => auth()->id(),
                'target_user_id' => $id ?? 'unknown'
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete account: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Failed to delete account');
        }
    }

    /**
     * Toggle account status (activate/deactivate)
     */
    public function toggleStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Check if this is an employee request
        if (request()->is('employee/*')) {
            // Employees cannot toggle account status
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employees cannot change account status'
                ], 403);
            }
            return back()->with('error', 'Employees cannot change account status');
        }
        
        \Log::info('Account toggle status request received', [
            'user_id' => $user->id,
            'current_status' => $user->email_verified_at ? 'active' : 'inactive',
            'user_email' => $user->email
        ]);

        try {
            // Prevent deactivating your own account
            if ($user->id === auth()->id()) {
                \Log::warning('User attempted to deactivate their own account', [
                    'user_id' => $user->id
                ]);
                
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You cannot deactivate your own account'
                    ], 400);
                }
                return back()->with('error', 'You cannot deactivate your own account');
            }

            $newStatus = $user->email_verified_at ? null : now();
            
            \Log::info('Updating user status', [
                'user_id' => $user->id,
                'old_status' => $user->email_verified_at,
                'new_status' => $newStatus
            ]);

            $user->update([
                'email_verified_at' => $newStatus
            ]);

            $status = $user->email_verified_at ? 'activated' : 'deactivated';

            \Log::info('User status updated successfully', [
                'user_id' => $user->id,
                'new_status' => $status,
                'email_verified_at' => $user->email_verified_at
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Account {$status} successfully",
                    'user' => $user
                ]);
            }

            return redirect()->route('accounts')->with('success', "Account {$status} successfully");
        } catch (\Exception $e) {
            \Log::error('Account toggle status failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update account status: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Failed to update account status');
        }
    }

    /**
     * Handle bulk actions on multiple accounts
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,delete',
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator);
        }

        try {
            $userIds = $request->user_ids;
            $action = $request->action;
            $currentUser = auth()->user();

            // Remove current user from bulk actions
            $userIds = array_filter($userIds, function($id) use ($currentUser) {
                return $id != $currentUser->id;
            });

            if (empty($userIds)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No valid accounts selected for bulk action'
                    ], 400);
                }
                return back()->with('error', 'No valid accounts selected for bulk action');
            }

            // For delete action, check role-based restrictions
            if ($action === 'delete') {
                $usersToDelete = User::whereIn('id', $userIds)->get();
            }

            $users = User::whereIn('id', $userIds);

            switch ($action) {
                case 'activate':
                    $users->update(['email_verified_at' => now()]);
                    $message = 'Accounts activated successfully';
                    break;
                case 'deactivate':
                    $users->update(['email_verified_at' => null]);
                    $message = 'Accounts deactivated successfully';
                    break;
                case 'delete':
                    $users->delete();
                    $message = 'Accounts deleted successfully';
                    break;
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'affected_count' => count($userIds)
                ]);
            }

            return redirect()->route('accounts')->with('success', $message);
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to perform bulk action: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Failed to perform bulk action');
        }
    }
}
