<?php

namespace Exceptions;

use Exception;
use Throwable;

class WrongType extends Exception
{
    public function __construct(
        string $message = "You choised wrong type",
        int $code = 0,
        Throwable|null $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
