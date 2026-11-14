<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Evitar que las deprecaciones de PHP 8.4 (p. ej. tempnam() en Filesystem::replace)
// se conviertan en ErrorException por el manejador de errores de Laravel.
error_reporting(E_ALL & ~E_DEPRECATED);

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

$app->handleRequest(Request::capture());
