<?php

namespace App\Foundation\Bootstrap;

use Illuminate\Foundation\Bootstrap\HandleExceptions as LaravelHandleExceptions;

class HandleExceptions extends LaravelHandleExceptions
{
    public function handleError($level, $message, $file = '', $line = 0)
    {
        if ($level === E_DEPRECATED || $level === E_USER_DEPRECATED) {
            return true;
        }

        if (error_reporting() & $level) {
            throw new \ErrorException($message, 0, $level, $file, $line);
        }
    }

    public function handleException($exception)
    {
        if ($exception instanceof \ErrorException && $exception->getSeverity() === E_DEPRECATED) {
            return;
        }

        parent::handleException($exception);
    }
}
