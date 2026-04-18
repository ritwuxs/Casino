<?php

namespace Exception\Exceptions;

use Exception;
use Throwable;

class InvalidGameTypeException extends \Exception // DO: Вынесем в корень папки + дадим более понятное название
{
    public function __construct(
        string $message = "You choised wrong type",
        int $code = 0,
        Throwable|null $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
