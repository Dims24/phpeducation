<?php

namespace App\Http\Service\Exceptions;
use Exception;
use Throwable;

class UserTokenExpiredException extends Exception
{
    public function __construct(string $message = "Время действия токена закночилось", int $code = 400, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }


}