<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

// Suprimir deprecaciones de PHP 8.4 (p. ej. tempnam() en Filesystem::replace)
// que el HandleExceptions de Laravel convertiría en ErrorException -> error 500.
// Se envuelve el handler de Laravel existente para no perder el manejo de otros errores.
$laravelHandler = set_error_handler(function () {
    return true;
}, E_ALL);

set_error_handler(function ($errno, $errstr, $errfile, $errline) use ($laravelHandler) {
    if ($errno === E_DEPRECATED || $errno === E_USER_DEPRECATED) {
        return true;
    }

    return $laravelHandler($errno, $errstr, $errfile, $errline);
}, E_ALL);

error_reporting(E_ALL & ~E_DEPRECATED);

$app->handleRequest(Request::capture());
