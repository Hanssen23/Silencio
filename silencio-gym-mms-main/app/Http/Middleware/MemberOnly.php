<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberOnly
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated via member guard
        if (!Auth::guard('member')->check()) {
            if (Auth::guard('web')->check()) {
                $user = Auth::guard('web')->user();
                if ($user->isAdmin()) {
                    return redirect()->route('dashboard');
                } elseif ($user->isEmployee()) {
                    return redirect()->route('employee.dashboard');
                } else {
                    return redirect()->route('dashboard');
                }
            }
            // Prevent redirect loop by checking if we're already on login page
            if (!$request->is('login') && !$request->is('/')) {
                return redirect()->route('login');
            }
            return redirect()->route('login');
        }

        // Additional security: Verify the member has member role
        $member = Auth::guard('member')->user();
        if (!$member || !$member->isMember()) {
            // If somehow a non-member user is logged in via member guard, logout and redirect
            Auth::guard('member')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('login')->withErrors([
                'email' => 'Access denied. Member privileges required.'
            ]);
        }

        return $next($request);
    }
}


