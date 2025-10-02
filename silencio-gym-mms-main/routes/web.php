<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RfidController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MemberAuthController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\MembershipPlanController;

// Public Authentication Routes
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login.show');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Employee Authentication Routes
Route::get('/employee/login', [AuthController::class, 'showEmployeeLogin'])->name('employee.auth.login.show');
Route::post('/employee/login', [AuthController::class, 'employeeLogin'])->name('employee.auth.login');

// CSRF Token refresh endpoint
Route::get('/csrf-token', function() {
    return response()->json(['csrf_token' => csrf_token()]);
})->name('csrf.token');

// Member self-registration
Route::get('/register', [MemberAuthController::class, 'showRegister'])->name('member.register');
Route::post('/register', [MemberAuthController::class, 'register'])->name('member.register.post');

// Public RFID Routes (for hardware integration) - bypass all middleware
Route::post('/rfid/tap', [RfidController::class, 'handleCardTap'])->withoutMiddleware(['web', 'auth']);
Route::get('/rfid/logs-public', [RfidController::class, 'getRfidLogs'])->withoutMiddleware(['web', 'auth']);

// Public pages
Route::get('/terms', function () {
    return view('terms');
})->name('terms');

// Authentication Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');

    // RFID Routes
    Route::get('/rfid-monitor', [RfidController::class, 'monitor'])->name('rfid.monitor');
    
    Route::prefix('rfid')->name('rfid.')->group(function () {
        Route::get('/start-system', [RfidController::class, 'startRfidSystem'])->name('start-system');
        Route::post('/start', [RfidController::class, 'startRfidReader'])->name('start');
        Route::get('/stop-system', [RfidController::class, 'stopRfidSystem'])->name('stop-system');
        Route::post('/stop', [RfidController::class, 'stopRfidReader'])->name('stop');
        Route::get('/status', [RfidController::class, 'getRfidStatus'])->name('status');
        Route::post('/tap', [RfidController::class, 'handleCardTap'])->name('tap');
        Route::get('/logs', [RfidController::class, 'getRfidLogs'])->name('logs');
        Route::get('/active-members', [RfidController::class, 'getActiveMembers'])->name('active-members');
        Route::get('/dashboard-stats', [RfidController::class, 'getDashboardStats'])->name('dashboard-stats');
        Route::get('/member-suggestions', [RfidController::class, 'getMemberSuggestions'])->name('member-suggestions');
        Route::post('/auto-tapout', [RfidController::class, 'manualAutoTapOut'])->name('auto-tapout');
    });

    // Member Routes
    Route::prefix('members')->name('members.')->group(function () {
        Route::get('/', [MemberController::class, 'index'])->name('index');
        Route::get('/create', [MemberController::class, 'create'])->name('create');
        Route::post('/', [MemberController::class, 'store'])->name('store');
        Route::get('/{id}', [MemberController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [MemberController::class, 'edit'])->name('edit');
        Route::put('/{id}', [MemberController::class, 'update'])->name('update');
        Route::delete('/{id}', [MemberController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/profile', [MemberController::class, 'profile'])->name('profile');
    });

    // Membership Management Routes
    Route::get('/membership/manage-member', [MembershipController::class, 'manageMember'])->name('membership.manage-member');
    Route::post('/membership/process-payment', [MembershipController::class, 'processPayment'])->name('membership.process-payment');
    Route::get('/membership/plans', [MembershipController::class, 'index'])->name('membership.plans.index');

    // Membership Plans API Routes
    Route::prefix('membership-plans')->name('membership-plans.')->group(function () {
        Route::get('/all', [MembershipController::class, 'getAllPlans'])->name('all');
        Route::post('/store', [MembershipController::class, 'store'])->name('store');
        Route::put('/{id}', [MembershipController::class, 'update'])->name('update');
        Route::delete('/{id}', [MembershipController::class, 'destroy'])->name('destroy');
        Route::get('/duration-types', [MembershipPlanController::class, 'getDurationTypes'])->name('duration-types');
        Route::post('/update-duration-types', [MembershipController::class, 'updateDurationTypes'])->name('update-duration-types');
    });

    // Payment Routes
    Route::prefix('membership/payments')->name('membership.payments.')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        Route::get('/create', [PaymentController::class, 'create'])->name('create');
        Route::post('/', [PaymentController::class, 'store'])->name('store');
        Route::get('/{id}', [PaymentController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PaymentController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PaymentController::class, 'update'])->name('update');
        Route::delete('/{id}', [PaymentController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/print', [PaymentController::class, 'print'])->name('print');
        Route::get('/{id}/details', [PaymentController::class, 'details'])->name('details');
        Route::get('/export/csv', [PaymentController::class, 'exportCsv'])->name('export_csv');
    });

    // Analytics Routes
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/weekly-revenue', [AnalyticsController::class, 'weeklyRevenue'])->name('weekly-revenue');
        Route::get('/weekly-attendance', [AnalyticsController::class, 'weeklyAttendance'])->name('weekly-attendance');
        Route::get('/rfid-activity', [AnalyticsController::class, 'rfidActivity'])->name('rfid-activity');
        Route::get('/dashboard-stats', [AnalyticsController::class, 'getDashboardStats'])->name('dashboard-stats');
    });

    // Account Management Routes
    Route::prefix('accounts')->name('accounts.')->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('index');
        Route::get('/create', [AccountController::class, 'create'])->name('create');
        Route::post('/', [AccountController::class, 'store'])->name('store');
        Route::get('/{id}', [AccountController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [AccountController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AccountController::class, 'update'])->name('update');
        Route::delete('/{id}', [AccountController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/toggle-status', [AccountController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/bulk-action', [AccountController::class, 'bulkAction'])->name('bulk-action');
    });

    // Employee Routes
    Route::prefix('employee')->name('employee.')->group(function () {
        Route::get('/dashboard', [EmployeeController::class, 'dashboard'])->name('dashboard');
        Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');
        Route::get('/rfid-monitor', [EmployeeController::class, 'rfidMonitor'])->name('rfid-monitor');
        Route::get('/members', [EmployeeController::class, 'members'])->name('members');
        Route::get('/members/create', [EmployeeController::class, 'createMember'])->name('members.create');
        Route::post('/members', [MemberController::class, 'store'])->name('members.store');
        Route::get('/members/{id}', [MemberController::class, 'show'])->name('members.show');
        Route::get('/members/{id}/edit', [MemberController::class, 'edit'])->name('members.edit');
        Route::put('/members/{id}', [MemberController::class, 'update'])->name('members.update');
        Route::get('/members/{id}/profile', [MemberController::class, 'profile'])->name('members.profile');
        Route::get('/payments', [EmployeeController::class, 'payments'])->name('payments');
        
        // Employee RFID Routes
        Route::prefix('rfid')->name('rfid.')->group(function () {
            Route::post('/start', [RfidController::class, 'startRfidReader'])->name('start');
            Route::post('/stop', [RfidController::class, 'stopRfidReader'])->name('stop');
            Route::get('/status', [RfidController::class, 'getRfidStatus'])->name('status');
        });
        
        // Employee Analytics Routes
        Route::prefix('analytics')->name('analytics.')->group(function () {
            Route::get('/weekly-attendance', [AnalyticsController::class, 'weeklyAttendance'])->name('weekly-attendance');
            Route::get('/weekly-revenue', [AnalyticsController::class, 'weeklyRevenue'])->name('weekly-revenue');
            Route::get('/monthly-revenue', [AnalyticsController::class, 'monthlyRevenue'])->name('monthly-revenue');
            Route::get('/dashboard-stats', [AnalyticsController::class, 'getDashboardStats'])->name('dashboard-stats');
        });
        
        // Employee Account Routes
        Route::prefix('accounts')->name('accounts.')->group(function () {
            Route::get('/', [AccountController::class, 'index'])->name('index');
            Route::get('/create', [AccountController::class, 'create'])->name('create');
            Route::post('/', [AccountController::class, 'store'])->name('store');
            Route::get('/{id}', [AccountController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [AccountController::class, 'edit'])->name('edit');
            Route::put('/{id}', [AccountController::class, 'update'])->name('update');
            Route::delete('/{id}', [AccountController::class, 'destroy'])->name('destroy');
        });
        
        // Employee Membership Routes
        Route::prefix('membership')->name('membership.')->group(function () {
            Route::get('/manage-member', [EmployeeController::class, 'manageMember'])->name('manage-member');
            Route::post('/process-payment', [EmployeeController::class, 'processPayment'])->name('process-payment');
            Route::get('/plans', [EmployeeController::class, 'plans'])->name('plans.index');
            Route::get('/payments', [EmployeeController::class, 'payments'])->name('payments');
            Route::get('/payments/{id}/details', [EmployeeController::class, 'paymentDetails'])->name('payments.details');
            Route::get('/payments/export/csv', [EmployeeController::class, 'exportPaymentsCsv'])->name('payments.export_csv');
            Route::get('/payments/{id}/print', [EmployeeController::class, 'printPayment'])->name('payments.print');
        });
        
        // Employee Membership Plans Routes
        Route::get('/membership-plans', [EmployeeController::class, 'plans'])->name('membership-plans');
        
        // Employee Membership Plans API Routes
        Route::prefix('membership-plans')->name('membership-plans.')->group(function () {
            Route::get('/all', [EmployeeController::class, 'getAllPlans'])->name('all');
            Route::get('/plan-types', [EmployeeController::class, 'getPlanTypes'])->name('plan-types');
            Route::get('/duration-types', [EmployeeController::class, 'getDurationTypes'])->name('duration-types');
        });
    });
});

// Member routes (member guard)
Route::middleware(['auth:member', 'member.only'])->group(function () {
    Route::get('/member', [\App\Http\Controllers\MemberDashboardController::class, 'index'])->name('member.dashboard');
    Route::get('/member/plans', [\App\Http\Controllers\MemberDashboardController::class, 'plans'])->name('member.plans');
    Route::get('/member/accounts', [\App\Http\Controllers\MemberDashboardController::class, 'accounts'])->name('member.accounts');
    Route::put('/member/profile', [\App\Http\Controllers\MemberDashboardController::class, 'updateProfile'])->name('member.profile.update');
    Route::get('/member/membership-plans', [\App\Http\Controllers\MemberDashboardController::class, 'membershipPlans'])->name('member.membership-plans');
    Route::get('/member/membership-plans/stream', [\App\Http\Controllers\MemberDashboardController::class, 'membershipPlansStream'])->name('member.membership-plans.stream');
    Route::get('/member/membership-pricing', [\App\Http\Controllers\MemberDashboardController::class, 'membershipPricing'])->name('member.membership-pricing');
});