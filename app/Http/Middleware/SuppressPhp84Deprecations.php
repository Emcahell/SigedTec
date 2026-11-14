<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SuppressPhp84Deprecations
{
    public function handle(Request $request, Closure $next)
    {
        $previous = null;
        $previous = set_error_handler(function ($errno, $errstr, $errfile, $errline) use (&$previous) {
            if ($errno === E_DEPRECATED || $errno === E_USER_DEPRECATED) {
                return true;
            }

            return $previous($errno, $errstr, $errfile, $errline);
        }, E_ALL);

        try {
            return $next($request);
        } finally {
            restore_error_handler();
        }
    }
}
