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
    ->withMiddleware(function (Middleware $middleware) {
        // Daftarkan alias middleware kamu di sini
        $middleware->alias([
            'is_admin' => \App\Http\Middleware\IsAdmin::class,
            'profile.completed' => \App\Http\Middleware\CheckProfileCompletion::class,
        ]);

        // Ini adalah cara yang benar untuk mengecualikan CSRF
        $middleware->validateCsrfTokens(except: [
            // hapus brodi '/midtrans/callback',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
