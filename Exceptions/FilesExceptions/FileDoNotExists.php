<?php

namespace Exceptions;

use Exception;
use Throwable;

class FileDoNotExists extends Exception
{
    public function __construct(
        string $message = "File do not exists",
        int $code = 0,
        Throwable|null $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
