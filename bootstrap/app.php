<?php

use App\Http\Middleware\CheckRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

// Evitar que las deprecaciones de PHP 8.4 (p. ej. tempnam()) se conviertan en ErrorException
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    if ($errno === E_DEPRECATED || $errno === E_USER_DEPRECATED) {
        return true;
    }

    return false;
});

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        /*
         * Alias equivalente a los aliases de app/Http/Kernel.php (Laravel 11+):
         * permite usar ->middleware('role:admin') en las rutas.
         */
        $middleware->alias([
            'role' => CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
