<?php

namespace Exception\Exceptions;

use Exception;
use Throwable;

class MinBetLimitException extends \Exception
{
    public function __construct(
        string $message = "Minimal bet is 10",
        int $code = 0,
        Throwable|null $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
