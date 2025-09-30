<?php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

/**
 * Dual Database Authentication Service
 * Integrates Python authentication system with Laravel
 */
class DualAuthService
{
    private $apiBaseUrl;
    private $timeout;
    
    public function __construct()
    {
        $this->apiBaseUrl = config('auth.dual_api_url', 'http://127.0.0.1:8002/api');
        $this->timeout = config('auth.dual_api_timeout', 10);
    }
    
    /**
     * Authenticate user through dual database system
     */
    public function authenticate(string $email, string $password, string $ipAddress = null): array
    {
        try {
            $response = Http::timeout($this->timeout)->post("{$this->apiBaseUrl}/auth/login", [
                'email' => $email,
                'password' => $password,
                'ip_address' => $ipAddress ?? request()->ip()
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['success']) {
                    // Cache the authentication result
                    Cache::put("auth_result_{$email}", $data, 3600);
                    
                    Log::info("Dual auth successful for {$email}", [
                        'user_type' => $data['user_type'],
                        'auth_source' => $data['auth_source']
                    ]);
                    
                    return [
                        'success' => true,
                        'user' => $data['user'],
                        'user_type' => $data['user_type'],
                        'auth_source' => $data['auth_source']
                    ];
                }
            }
            
            Log::warning("Dual auth failed for {$email}", [
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            
            return [
                'success' => false,
                'error' => $response->json()['error'] ?? 'Authentication failed'
            ];
            
        } catch (\Exception $e) {
            Log::error("Dual auth error for {$email}: " . $e->getMessage());
            
            // Fallback to emergency admin
            if ($this->isEmergencyAdmin($email, $password)) {
                return [
                    'success' => true,
                    'user' => [
                        'id' => 0,
                        'email' => $email,
                        'name' => 'Emergency Administrator',
                        'role' => 'admin'
                    ],
                    'user_type' => 'admin',
                    'auth_source' => 'emergency'
                ];
            }
            
            return [
                'success' => false,
                'error' => 'Authentication service unavailable'
            ];
        }
    }
    
    /**
     * Validate session through dual database system
     */
    public function validateSession(string $sessionId): array
    {
        try {
            $response = Http::timeout($this->timeout)->get("{$this->apiBaseUrl}/auth/validate", [
                'session_id' => $sessionId
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                return $data;
            }
            
            return [
                'success' => false,
                'error' => 'Session validation failed'
            ];
            
        } catch (\Exception $e) {
            Log::error("Session validation error: " . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Session validation service unavailable'
            ];
        }
    }
    
    /**
     * Get system health status
     */
    public function getHealthStatus(): array
    {
        try {
            $response = Http::timeout($this->timeout)->get("{$this->apiBaseUrl}/health");
            
            if ($response->successful()) {
                return $response->json();
            }
            
            return [
                'success' => false,
                'error' => 'Health check failed'
            ];
            
        } catch (\Exception $e) {
            Log::error("Health check error: " . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Health check service unavailable'
            ];
        }
    }
    
    /**
     * Get authentication statistics
     */
    public function getStatistics(): array
    {
        try {
            $response = Http::timeout($this->timeout)->get("{$this->apiBaseUrl}/stats");
            
            if ($response->successful()) {
                return $response->json();
            }
            
            return [
                'success' => false,
                'error' => 'Statistics retrieval failed'
            ];
            
        } catch (\Exception $e) {
            Log::error("Statistics error: " . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Statistics service unavailable'
            ];
        }
    }
    
    /**
     * Check if credentials match emergency admin
     */
    private function isEmergencyAdmin(string $email, string $password): bool
    {
        return $email === 'emergency@admin.com' && $password === 'EmergencyAdmin123!';
    }
    
    /**
     * Create admin user through dual database system
     */
    public function createAdmin(string $email, string $password, string $name, string $role = 'admin'): array
    {
        try {
            $response = Http::timeout($this->timeout)->post("{$this->apiBaseUrl}/admin/users", [
                'email' => $email,
                'password' => $password,
                'name' => $name,
                'role' => $role
            ]);
            
            if ($response->successful()) {
                return $response->json();
            }
            
            return [
                'success' => false,
                'error' => $response->json()['error'] ?? 'Admin creation failed'
            ];
            
        } catch (\Exception $e) {
            Log::error("Admin creation error: " . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Admin creation service unavailable'
            ];
        }
    }
    
    /**
     * Get all admin users
     */
    public function getAdminUsers(): array
    {
        try {
            $response = Http::timeout($this->timeout)->get("{$this->apiBaseUrl}/admin/users");
            
            if ($response->successful()) {
                return $response->json();
            }
            
            return [
                'success' => false,
                'error' => 'Admin users retrieval failed'
            ];
            
        } catch (\Exception $e) {
            Log::error("Admin users retrieval error: " . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Admin users service unavailable'
            ];
        }
    }
    
    /**
     * Repair corrupted databases
     */
    public function repairDatabases(): array
    {
        try {
            $response = Http::timeout($this->timeout)->post("{$this->apiBaseUrl}/corruption/repair");
            
            if ($response->successful()) {
                return $response->json();
            }
            
            return [
                'success' => false,
                'error' => 'Database repair failed'
            ];
            
        } catch (\Exception $e) {
            Log::error("Database repair error: " . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Database repair service unavailable'
            ];
        }
    }
    
    /**
     * Get corruption history
     */
    public function getCorruptionHistory(): array
    {
        try {
            $response = Http::timeout($this->timeout)->get("{$this->apiBaseUrl}/corruption/history");
            
            if ($response->successful()) {
                return $response->json();
            }
            
            return [
                'success' => false,
                'error' => 'Corruption history retrieval failed'
            ];
            
        } catch (\Exception $e) {
            Log::error("Corruption history error: " . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Corruption history service unavailable'
            ];
        }
    }
}
