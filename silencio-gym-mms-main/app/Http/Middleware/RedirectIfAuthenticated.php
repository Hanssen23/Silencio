<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                
                // Redirect based on user type
                if ($guard === 'member') {
                    return redirect()->route('member.dashboard');
                } elseif ($user->isAdmin()) {
                    return redirect()->route('dashboard');
                } elseif ($user->isEmployee()) {
                    return redirect()->route('employee.dashboard');
                } else {
                    return redirect()->route('dashboard');
                }
            }
        }

        return $next($request);
    }
}
