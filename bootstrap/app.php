<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminOnly;
use App\Http\Middleware\SetLocaleAndCurrency;
use App\Http\Middleware\SecurityHeaders;
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => AdminOnly::class,
        ]);

        $middleware->web(append: [SetLocaleAndCurrency::class, SecurityHeaders::class]);

        // Stripe calls this endpoint server-to-server, so there is no CSRF token;
        // the request is authenticated by its signature instead.
        $middleware->validateCsrfTokens(except: ['stripe/webhook']);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
