<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RfidController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\EmployeeController;

Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

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
    });

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

    Route::prefix('membership/payments')->name('membership.payments.')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        Route::get('/create', [PaymentController::class, 'create'])->name('create');
        Route::post('/', [PaymentController::class, 'store'])->name('store');
        Route::get('/{id}', [PaymentController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PaymentController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PaymentController::class, 'update'])->name('update');
        Route::delete('/{id}', [PaymentController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/print', [PaymentController::class, 'print'])->name('print');
        Route::get('/export/csv', [PaymentController::class, 'exportCsv'])->name('export_csv');
    });

    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/weekly-revenue', [AnalyticsController::class, 'weeklyRevenue'])->name('weekly-revenue');
        Route::get('/weekly-attendance', [AnalyticsController::class, 'weeklyAttendance'])->name('weekly-attendance');
        Route::get('/rfid-activity', [AnalyticsController::class, 'rfidActivity'])->name('rfid-activity');
    });

    Route::prefix('accounts')->name('accounts.')->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('index');
        Route::get('/create', [AccountController::class, 'create'])->name('create');
        Route::post('/', [AccountController::class, 'store'])->name('store');
        Route::get('/{id}', [AccountController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [AccountController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AccountController::class, 'update'])->name('update');
        Route::delete('/{id}', [AccountController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('employee')->name('employee.')->group(function () {
        Route::get('/dashboard', [EmployeeController::class, 'dashboard'])->name('dashboard');
        Route::get('/rfid-monitor', [EmployeeController::class, 'rfidMonitor'])->name('rfid-monitor');
        Route::get('/members', [EmployeeController::class, 'members'])->name('members');
        Route::get('/payments', [EmployeeController::class, 'payments'])->name('payments');
    });
});

Route::prefix('rfid')->group(function () {
    Route::get('/logs-public', [RfidController::class, 'getRfidLogs']);
    Route::get('/active-members-public', [RfidController::class, 'getActiveMembers']);
});
