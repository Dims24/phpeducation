<?php

namespace App\Http\Exceptions;
use Exception;
use Throwable;

class AccessDeniedException extends Exception
{
    public function __construct(string $message = "Доступ запрещен", int $code = 401, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}