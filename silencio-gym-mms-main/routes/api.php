<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RfidController;

// Public RFID API Routes (no middleware)
Route::post('/rfid/tap', [RfidController::class, 'handleCardTap']);
Route::get('/rfid/logs-public', [RfidController::class, 'getRfidLogs']);

// Protected RFID API Routes (require authentication)
Route::middleware('auth')->group(function () {
    Route::get('/rfid/active-members', [RfidController::class, 'getActiveMembers']);
    Route::get('/rfid/dashboard-stats', [RfidController::class, 'getDashboardStats']);
    Route::get('/rfid/logs', [RfidController::class, 'getRfidLogs']);
    Route::post('/rfid/start', [RfidController::class, 'startRfidReader']);
    Route::post('/rfid/stop', [RfidController::class, 'stopRfidReader']);
    Route::get('/rfid/status', [RfidController::class, 'getRfidStatus']);
    Route::get('/rfid/consistency-check', [RfidController::class, 'checkDataConsistency']);
});