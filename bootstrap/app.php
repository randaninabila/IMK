<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role'           => \app\Http\Middleware\RoleMiddleware::class,
            'phone.verified' => \app\Http\Middleware\EnsurePhoneVerified::class,
            'reg.complete'   => \app\Http\Middleware\EnsureRegistrationComplete::class, // tambah ini
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
