<?php

namespace Exception\Exceptions;

use Exception;
use Throwable;

class InvalidArgumentException extends \Exception
{
    public function __construct(
        string $message = "Something went wrong with json file",
        int $code = 0,
        Throwable|null $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
