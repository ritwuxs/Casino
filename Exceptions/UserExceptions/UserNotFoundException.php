<?php

namespace Exceptions;

use Exception;
use Throwable;

class UserNotFoundException extends Exception
{
    public function __construct(
        string $message = "User not found in the system",
        int $code = 0,
        Throwable |null $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
