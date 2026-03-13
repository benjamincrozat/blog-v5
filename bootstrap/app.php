<?php

use Sentry\Laravel\Integration;
use Illuminate\Foundation\Application;
use App\Http\Middleware\HandleRedirects;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: [
            __DIR__ . '/../routes/auth.php',
            __DIR__ . '/../routes/legacy.php',
            __DIR__ . '/../routes/user.php',
            __DIR__ . '/../routes/shortener.php',
            __DIR__ . '/../routes/guest.php',
        ],
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware
            ->redirectGuestsTo('/login')
            ->append(HandleRedirects::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        Integration::handles($exceptions);
    })
    ->create();
