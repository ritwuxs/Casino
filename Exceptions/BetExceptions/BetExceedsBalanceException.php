<?php

namespace Exceptions;

use Exception;
use Throwable;

class BetExceedsBalanceException extends Exception
{
    public function __construct(
        string $message = "Bet can not be bigger than balance",
        int $code = 0,
        Throwable|null $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
