<?php

namespace App\Http\Controllers\Auth\Exceptions;

use Exception;
use Throwable;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;

class RegistrationValidationException extends Exception
{
    public function __construct(string $message = "Некорректные данные", int $code = 400, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}