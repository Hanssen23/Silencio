<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

class RouteValidationMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Only run in development or when explicitly enabled
        if (!app()->environment('local') && !config('app.route_validation_enabled', false)) {
            return $next($request);
        }

        $response = $next($request);

        // Check if this is a route that should be validated
        if ($this->shouldValidateRoute($request)) {
            $this->validateRoute($request);
        }

        return $response;
    }

    private function shouldValidateRoute(Request $request)
    {
        // Skip validation for certain routes
        $skipRoutes = [
            'sanctum.*',
            'ignition.*',
            'telescope.*',
            'horizon.*',
            'storage.*',
        ];

        $routeName = $request->route()?->getName();
        
        if (!$routeName) {
            return false;
        }

        foreach ($skipRoutes as $pattern) {
            if (fnmatch($pattern, $routeName)) {
                return false;
            }
        }

        return true;
    }

    private function validateRoute(Request $request)
    {
        $routeName = $request->route()?->getName();
        
        if (!$routeName) {
            return;
        }

        // Check if route exists
        if (!Route::has($routeName)) {
            Log::warning("Route validation failed: Route '{$routeName}' does not exist", [
                'url' => $request->url(),
                'method' => $request->method(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        // Check for common route issues
        $this->checkForCommonIssues($request, $routeName);
    }

    private function checkForCommonIssues(Request $request, $routeName)
    {
        // Check for missing parameters
        $route = Route::getRoutes()->getByName($routeName);
        if ($route) {
            $requiredParams = $route->parameterNames();
            $providedParams = array_keys($request->route()->parameters());
            
            $missingParams = array_diff($requiredParams, $providedParams);
            if (!empty($missingParams)) {
                Log::warning("Route validation: Missing parameters for route '{$routeName}'", [
                    'missing_params' => $missingParams,
                    'required_params' => $requiredParams,
                    'provided_params' => $providedParams,
                ]);
            }
        }
    }
}
