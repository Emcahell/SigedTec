<?php

namespace App\Foundation\Bootstrap;

use Illuminate\Foundation\Bootstrap\HandleExceptions as LaravelHandleExceptions;

class HandleExceptions extends LaravelHandleExceptions
{
    /**
     * Ignora deprecaciones y el E_NOTICE conocido de tempnam() en PHP 8.4+,
     * que Laravel convierte en ErrorException y provoca 500 al compilar Blade.
     *
     * La causa real suele ser storage no escribible; el entrypoint corrige eso.
     * Este filtro evita el 500 si el notice aparece de todas formas.
     */
    public function handleError($level, $message, $file = '', $line = 0)
    {
        if ($level === E_DEPRECATED || $level === E_USER_DEPRECATED) {
            return true;
        }

        if (
            ($level === E_NOTICE || $level === E_WARNING)
            && str_contains((string) $message, 'tempnam(): file created in the system\'s temporary directory')
        ) {
            return true;
        }

        if (error_reporting() & $level) {
            throw new \ErrorException($message, 0, $level, $file, $line);
        }

        return true;
    }

    public function handleException($exception)
    {
        if (
            $exception instanceof \ErrorException
            && (
                $exception->getSeverity() === E_DEPRECATED
                || (
                    ($exception->getSeverity() === E_NOTICE || $exception->getSeverity() === E_WARNING)
                    && str_contains($exception->getMessage(), 'tempnam(): file created in the system\'s temporary directory')
                )
            )
        ) {
            return;
        }

        parent::handleException($exception);
    }
}
