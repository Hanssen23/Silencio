<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'rfid/tap', // Exclude RFID tap route from CSRF protection
        'rfid/*', // Exclude all RFID routes from CSRF protection
    ];
}

