<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CacheResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Don't cache if:
        // 1. Request is not GET
        // 2. Request is from logged-in user
        // 3. Request has query parameters
        if ($request->method() !== 'GET' || 
            $request->user() || 
            count($request->query()) > 0) {
            return $next($request);
        }

        // Generate cache key from full URL
        $key = 'response_' . sha1($request->fullUrl());

        // Return cached response if exists
        if (Cache::has($key)) {
            return Cache::get($key);
        }

        $response = $next($request);

        // Cache the response for 1 hour if it's successful
        if ($response->status() === 200) {
            Cache::put($key, $response, now()->addHour());
        }

        return $response;
    }
}
