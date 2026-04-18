<?php

namespace Exception\Exceptions;

use Exception;
use Throwable;

class NegativeAmountException extends \Exception
{
    public function __construct(
        string $message = "Amount could not be negative",
        int $code = 0,
        Throwable|null $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
