<?php

namespace Tests\Unit;

use App\Foundation\Bootstrap\HandleExceptions;
use PHPUnit\Framework\TestCase;

class HandleExceptionsTempnamTest extends TestCase
{
    public function test_tempnam_system_temp_notice_is_suppressed(): void
    {
        $handler = new HandleExceptions;

        $this->assertTrue(
            $handler->handleError(
                E_NOTICE,
                "tempnam(): file created in the system's temporary directory",
                'vendor/laravel/framework/src/Illuminate/Filesystem/Filesystem.php',
                226,
            ),
        );
    }

    public function test_other_notices_still_throw_error_exception(): void
    {
        $previous = error_reporting(E_ALL);
        $handler = new HandleExceptions;

        try {
            $this->expectException(\ErrorException::class);
            $this->expectExceptionMessage('Undefined variable $foo');

            $handler->handleError(
                E_NOTICE,
                'Undefined variable $foo',
                'app/Example.php',
                10,
            );
        } finally {
            error_reporting($previous);
        }
    }
}
