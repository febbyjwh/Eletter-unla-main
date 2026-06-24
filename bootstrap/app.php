<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'active.user' => \App\Http\Middleware\ActiveUserMiddleware::class,
            'active.unit' => \App\Http\Middleware\ActiveUnitMiddleware::class,
            'unit' => \App\Http\Middleware\UnitAuthMiddleware::class,

            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })

    ->withMiddleware(function ($middleware) {
        $middleware->trustProxies(
            at: '*',
            headers: \Illuminate\Http\Request::HEADER_X_FORWARDED_FOR
                | \Illuminate\Http\Request::HEADER_X_FORWARDED_HOST
                | \Illuminate\Http\Request::HEADER_X_FORWARDED_PORT
                | \Illuminate\Http\Request::HEADER_X_FORWARDED_PROTO
        );
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })

    ->create();
