<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        // Debug logging
        \Log::info('AdminOnly Middleware Debug', [
            'url' => $request->url(),
            'method' => $request->method(),
            'web_authenticated' => Auth::guard('web')->check(),
            'member_authenticated' => Auth::guard('member')->check(),
            'user_id' => Auth::guard('web')->check() ? Auth::guard('web')->user()->id : null,
            'user_role' => Auth::guard('web')->check() ? Auth::guard('web')->user()->role : null,
            'is_admin' => Auth::guard('web')->check() ? Auth::guard('web')->user()->isAdmin() : null,
        ]);

        // Check if user is authenticated via web guard (admin guard)
        if (!Auth::guard('web')->check()) {
            if (Auth::guard('member')->check()) {
                return redirect()->route('member.dashboard');
            }
            // Prevent redirect loop by checking if we're already on login page
            if (!$request->is('login') && !$request->is('/')) {
                return redirect()->route('login');
            }
            return redirect()->route('login');
        }

        // Additional security: Verify the user has admin role
        $user = Auth::guard('web')->user();
        if (!$user || !$user->isAdmin()) {
            // If somehow a non-admin user is logged in via web guard, redirect based on role instead of logout
            if ($user && $user->isEmployee()) {
                \Log::warning('Employee user tried to access admin route', [
                    'user_id' => $user->id,
                    'user_role' => $user->role,
                    'url' => $request->url()
                ]);
                return redirect()->route('employee.dashboard')->withErrors([
                    'email' => 'Access denied. Admin privileges required.'
                ]);
            }
            
            // For other cases, logout and redirect
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('login')->withErrors([
                'email' => 'Access denied. Admin privileges required.'
            ]);
        }

        return $next($request);
    }
}


