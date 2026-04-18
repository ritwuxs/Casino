<?php

namespace Exception\Exceptions;

use Exception;
use Throwable;


class UserAlreadyExistsException extends \Exception
{
    public function __construct(
        string $message = "User arleady exists.UserName has to be uniq",
        int $code = 0,
        Throwable|null $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
