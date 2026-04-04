<?php

namespace Exceptions;

use Exception;
use Throwable;

class NegativeBetException extends Exception
{
    public function __construct(
        string $message = "Bet can not be negative",
        int $code = 0,
        Throwable|null $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
