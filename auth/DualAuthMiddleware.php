<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\DualAuthService;
use Illuminate\Support\Facades\Log;

/**
 * Dual Database Authentication Middleware
 * Integrates with Python authentication system
 */
class DualAuthMiddleware
{
    private $dualAuthService;
    
    public function __construct(DualAuthService $dualAuthService)
    {
        $this->dualAuthService = $dualAuthService;
    }
    
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $userType = 'admin')
    {
        try {
            // Check if user is already authenticated via Laravel session
            if (auth()->check()) {
                $user = auth()->user();
                
                // Verify user type matches required type
                if ($this->isUserTypeValid($user, $userType)) {
                    return $next($request);
                }
            }
            
            // Try dual authentication system
            $authResult = $this->authenticateViaDualSystem($request);
            
            if ($authResult['success']) {
                // Set Laravel session
                $this->setLaravelSession($authResult['user'], $authResult['user_type']);
                
                // Verify user type
                if ($this->isUserTypeValid($authResult['user'], $userType)) {
                    return $next($request);
                }
            }
            
            // Authentication failed
            return $this->handleAuthenticationFailure($request, $userType);
            
        } catch (\Exception $e) {
            Log::error("Dual auth middleware error: " . $e->getMessage());
            
            // Fallback to emergency admin for critical operations
            if ($this->isEmergencyAccess($request)) {
                return $next($request);
            }
            
            return redirect()->route('login')->with('error', 'Authentication service unavailable');
        }
    }
    
    /**
     * Authenticate via dual database system
     */
    private function authenticateViaDualSystem(Request $request): array
    {
        $email = $request->input('email');
        $password = $request->input('password');
        
        if (!$email || !$password) {
            return ['success' => false, 'error' => 'Credentials required'];
        }
        
        return $this->dualAuthService->authenticate($email, $password, $request->ip());
    }
    
    /**
     * Set Laravel session after successful dual authentication
     */
    private function setLaravelSession(array $user, string $userType): void
    {
        // Create a temporary user object for Laravel session
        $laravelUser = new \stdClass();
        $laravelUser->id = $user['id'];
        $laravelUser->email = $user['email'];
        $laravelUser->name = $user['name'];
        $laravelUser->role = $user['role'] ?? $userType;
        
        // Set Laravel authentication
        auth()->login($laravelUser);
        
        // Store additional session data
        session([
            'dual_auth_source' => 'dual_system',
            'user_type' => $userType,
            'auth_timestamp' => now()
        ]);
    }
    
    /**
     * Check if user type is valid for the required type
     */
    private function isUserTypeValid($user, string $requiredType): bool
    {
        if (!$user) {
            return false;
        }
        
        $userRole = $user->role ?? 'member';
        
        switch ($requiredType) {
            case 'admin':
                return in_array($userRole, ['admin', 'super_admin']);
            case 'employee':
                return in_array($userRole, ['admin', 'super_admin', 'employee']);
            case 'member':
                return in_array($userRole, ['admin', 'super_admin', 'employee', 'member']);
            default:
                return true;
        }
    }
    
    /**
     * Handle authentication failure
     */
    private function handleAuthenticationFailure(Request $request, string $userType)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'error' => 'Authentication required',
                'required_type' => $userType
            ], 401);
        }
        
        $errorMessage = $this->getErrorMessage($userType);
        return redirect()->route('login')->with('error', $errorMessage);
    }
    
    /**
     * Get appropriate error message based on user type
     */
    private function getErrorMessage(string $userType): string
    {
        switch ($userType) {
            case 'admin':
                return 'Admin privileges required. Please login with admin credentials.';
            case 'employee':
                return 'Employee or admin access required.';
            case 'member':
                return 'Member access required. Please login to continue.';
            default:
                return 'Authentication required. Please login to continue.';
        }
    }
    
    /**
     * Check if this is emergency access
     */
    private function isEmergencyAccess(Request $request): bool
    {
        $email = $request->input('email');
        $password = $request->input('password');
        
        return $email === 'emergency@admin.com' && $password === 'EmergencyAdmin123!';
    }
}

/**
 * Admin Only Middleware (using dual auth)
 */
class DualAdminOnly extends DualAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        return parent::handle($request, $next, 'admin');
    }
}

/**
 * Employee Only Middleware (using dual auth)
 */
class DualEmployeeOnly extends DualAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        return parent::handle($request, $next, 'employee');
    }
}

/**
 * Member Only Middleware (using dual auth)
 */
class DualMemberOnly extends DualAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        return parent::handle($request, $next, 'member');
    }
}
