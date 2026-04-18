<?php

namespace Exception\Exceptions;

use Exception;
use Throwable;

class InsufficientBalanceException extends \Exception
{

    public function __construct(
        string $message = "Balance can't be < 0",
        int $code = 0,
        Throwable|null $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
