<?php

namespace Exceptions;

use Exception;
use Throwable;

class InsufficientFundsException extends Exception
{

    public function __construct(
        string $message = "Not enough money",
        int $code = 0,
        Throwable|null $previous = null
    ) {
         parent::__construct($message, $code, $previous);
    }
}
