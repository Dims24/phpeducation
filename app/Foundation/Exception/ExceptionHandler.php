<?php

namespace App\Foundation\Exception;

use App\Foundation\HTTP\Response;
//use App\Foundation\Logger\Logger;
use Carbon\Carbon;
use JetBrains\PhpStorm\NoReturn;
use Throwable;
use App\Foundation\Logger\Logger;

class ExceptionHandler
{

    #[NoReturn] public static function handleException(Throwable $exception): void
    {
        $exception_body = [
            'time' => Carbon::now('Europe/Moscow')->format('Y-m-d H:i:s'),
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTrace(),
        ];
        $log = new Logger("errors");

        $log->log($exception_body);

        $response = new Response($exception_body, (int) $exception->getCode());

        $response->send();

        exit(1);
    }

    public static function handleError(
        int    $error_level,
        string $message,
        string $file = null,
        int    $line = null,
        array  $context = null
    ): bool
    {
        $error_body = [
            'time' => Carbon::now('Europe/Moscow')->format('Y-m-d H:i:s'),
            'level' => $message,
            'code' => $error_level,
            'file' => $file,
            'line' => $line,
            'context' => $context,
        ];

        $log = new Logger("fatal_errors");
        $log->log($error_body);

        $response = new Response($error_body, 400);

        $response->send();

        return true;
    }
}
