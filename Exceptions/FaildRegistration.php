<?php

namespace Exception\Exceptions;

use Exception;
use Throwable;

class FaildRegistration extends \Exception
{
    public function __construct(
        string $message = "Registration failed",
        int $code = 0,
        Throwable|null $previous = null
    ) {
         parent::__construct($message, $code, $previous);
    }
}
