<?php

use App\Foundation\Bootstrap\HandleExceptions as CustomHandleExceptions;
use App\Http\Middleware\CheckRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Bootstrap\HandleExceptions;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

$app = Application::configure(basePath: dirname(__DIR__))
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

// Reemplazar el HandleExceptions de Laravel con nuestra versión que suprime
// las deprecaciones de PHP 8.4 (p. ej. tempnam()) para evitar ErrorException -> error 500.
$app->singleton(
    HandleExceptions::class,
    CustomHandleExceptions::class
);

return $app;
