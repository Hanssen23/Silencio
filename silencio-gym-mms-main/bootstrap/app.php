<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'start.rfid' => \App\Http\Middleware\StartRfidReader::class,
            'error.handler' => \App\Http\Middleware\ErrorHandler::class,
            'admin.only' => \App\Http\Middleware\AdminOnly::class,
            'employee.only' => \App\Http\Middleware\EmployeeOnly::class,
            'member.only' => \App\Http\Middleware\MemberOnly::class,
            'prevent.back' => \App\Http\Middleware\PreventBackHistory::class,
            'prevent.member.admin' => \App\Http\Middleware\PreventMemberAdminAccess::class,
            'ensure.session' => \App\Http\Middleware\EnsureSessionPersistence::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
