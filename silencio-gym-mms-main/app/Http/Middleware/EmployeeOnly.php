<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EmployeeOnly
{
    public function handle(Request $request, Closure $next)
    {
        // Enhanced debug logging
        Log::info('EmployeeOnly Middleware Debug', [
            'url' => $request->url(),
            'method' => $request->method(),
            'web_authenticated' => Auth::guard('web')->check(),
            'member_authenticated' => Auth::guard('member')->check(),
            'user_id' => Auth::guard('web')->check() ? Auth::guard('web')->user()->id : null,
            'user_role' => Auth::guard('web')->check() ? Auth::guard('web')->user()->role : null,
            'is_employee' => Auth::guard('web')->check() ? Auth::guard('web')->user()->isEmployee() : null,
            'session_id' => $request->session()->getId(),
            'ajax_request' => $request->ajax(),
        ]);

        // Check if user is authenticated via web guard (admin guard)
        if (!Auth::guard('web')->check()) {
            if (Auth::guard('member')->check()) {
                return redirect()->route('member.dashboard');
            }
            
            // For AJAX requests, return JSON response instead of redirect
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => 'Authentication required',
                    'redirect' => route('login')
                ], 401);
            }
            
            // Prevent redirect loop by checking if we're already on login page
            if (!$request->is('login') && !$request->is('/')) {
                return redirect()->route('login');
            }
            return redirect()->route('login');
        }

        // Additional security: Verify the user has employee role
        $user = Auth::guard('web')->user();
        if (!$user) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => 'User not found',
                    'redirect' => route('login')
                ], 401);
            }
            return redirect()->route('login');
        }
        
        if (!$user->isEmployee()) {
            // Don't logout, just redirect based on role
            if ($user->isAdmin()) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'error' => 'Admin access required',
                        'redirect' => route('dashboard')
                    ], 403);
                }
                return redirect()->route('dashboard');
            } else {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'error' => 'Employee privileges required',
                        'redirect' => route('login')
                    ], 403);
                }
                return redirect()->route('login')->withErrors([
                    'email' => 'Access denied. Employee privileges required.'
                ]);
            }
        }

        // Extend session for active employee users
        $request->session()->put('last_activity', time());
        $request->session()->put('user_role', $user->role);

        return $next($request);
    }
}
