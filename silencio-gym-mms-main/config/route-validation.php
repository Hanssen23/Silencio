<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Route Validation Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration file controls the route validation system
    | for the Silencio Gym Management System.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Enable Route Validation
    |--------------------------------------------------------------------------
    |
    | Set to true to enable route validation in production.
    | Recommended to keep false in production for performance.
    |
    */
    'enabled' => env('ROUTE_VALIDATION_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Validation Environments
    |--------------------------------------------------------------------------
    |
    | Specify which environments should have route validation enabled.
    |
    */
    'environments' => ['local', 'development', 'testing'],

    /*
    |--------------------------------------------------------------------------
    | Skip Routes
    |--------------------------------------------------------------------------
    |
    | Routes that should be skipped during validation.
    | Use wildcard patterns for matching.
    |
    */
    'skip_routes' => [
        'sanctum.*',
        'ignition.*',
        'telescope.*',
        'horizon.*',
        'storage.*',
        'debugbar.*',
    ],

    /*
    |--------------------------------------------------------------------------
    | Critical Routes
    |--------------------------------------------------------------------------
    |
    | Routes that are critical for the application to function.
    | These will be checked more thoroughly.
    |
    */
    'critical_routes' => [
        'login',
        'logout',
        'dashboard',
        'member.dashboard',
        'employee.dashboard',
        'analytics.dashboard-stats',
        'rfid.status',
        'rfid.start',
        'rfid.stop',
    ],

    /*
    |--------------------------------------------------------------------------
    | Route Categories
    |--------------------------------------------------------------------------
    |
    | Define route categories for better organization and validation.
    |
    */
    'categories' => [
        'authentication' => [
            'login',
            'logout',
            'register',
            'member.register',
            'employee.auth.login',
        ],
        'dashboard' => [
            'dashboard',
            'dashboard.stats',
            'analytics.*',
        ],
        'members' => [
            'members.*',
            'member.*',
        ],
        'payments' => [
            'membership.*',
            'payment.*',
        ],
        'rfid' => [
            'rfid.*',
        ],
        'employee' => [
            'employee.*',
        ],
        'api' => [
            'api.*',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Validation Rules
    |--------------------------------------------------------------------------
    |
    | Define validation rules for different types of routes.
    |
    */
    'rules' => [
        'required_parameters' => [
            'members.edit' => ['id'],
            'members.show' => ['id'],
            'members.update' => ['id'],
            'members.destroy' => ['id'],
            'payments.show' => ['id'],
            'payments.edit' => ['id'],
            'payments.update' => ['id'],
            'payments.destroy' => ['id'],
        ],
        'required_middleware' => [
            'dashboard' => ['auth'],
            'members.*' => ['auth'],
            'payments.*' => ['auth'],
            'rfid.*' => ['auth'],
            'employee.*' => ['auth', 'employee.only'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Reporting
    |--------------------------------------------------------------------------
    |
    | Configuration for route validation reporting.
    |
    */
    'reporting' => [
        'enabled' => true,
        'log_missing_routes' => true,
        'log_unused_routes' => true,
        'generate_reports' => true,
        'report_path' => storage_path('logs/route-validation'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Auto-fix
    |--------------------------------------------------------------------------
    |
    | Configuration for automatic route fixing.
    |
    */
    'auto_fix' => [
        'enabled' => env('ROUTE_AUTO_FIX_ENABLED', false),
        'backup_before_fix' => true,
        'fix_missing_routes' => true,
        'remove_unused_routes' => false,
    ],
];
