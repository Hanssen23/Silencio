<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Member;


class AuthController extends Controller
{
    public function showLogin()
    {
        // Only redirect if user is already authenticated and not already on login page
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->isAdmin()) {
                return redirect()->route('dashboard');
            } elseif ($user->isEmployee()) {
                return redirect()->route('employee.dashboard');
            }
        }
        
        if (Auth::guard('member')->check()) {
            return redirect()->route('member.dashboard');
        }

        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // Attempt user login (admin/employee)
        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::guard('web')->user();
            
            // Check if account is activated
            if (!$user->email_verified_at) {
                Auth::guard('web')->logout();
                return back()->withErrors([
                    'email' => 'Your account is not activated. Please contact an administrator.',
                ])->withInput($request->only('email'));
            }
            
            // Regenerate session for security
            $request->session()->regenerate();
            
            // Redirect based on user role
            if ($user->isAdmin()) {
                return redirect()->route('dashboard');
            } elseif ($user->isEmployee()) {
                return redirect()->route('employee.dashboard');
            } else {
                // Fallback for any other role
                return redirect()->route('dashboard');
            }
        }

        // Attempt member login
        if (Auth::guard('member')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/member');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        try {
            // Logout from both guards if logged in
            if (Auth::guard('web')->check()) {
                Auth::guard('web')->logout();
            }
            if (Auth::guard('member')->check()) {
                Auth::guard('member')->logout();
            }
            
            // Invalidate session and regenerate token
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect('/')->with('success', 'You have been logged out successfully.');
            
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Logout error: ' . $e->getMessage());
            
            // Force logout even if there's an error
            Auth::logout();
            $request->session()->flush();
            
            return redirect('/')->with('error', 'Session expired. You have been logged out.');
        }
    }
} 