<?php

namespace Exceptions;

use Exception;
use Throwable;

class WrongType extends Exception // TODO: Вынесем в корень папки + дадим более понятное название
{
    public function __construct(
        string $message = "You choised wrong type",
        int $code = 0,
        Throwable|null $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
