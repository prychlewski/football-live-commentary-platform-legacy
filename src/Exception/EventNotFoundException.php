<?php

namespace App\Exception;

use Throwable;

class EventNotFoundException extends \Exception
{
    public function __construct($message = 'Sport event not found', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
