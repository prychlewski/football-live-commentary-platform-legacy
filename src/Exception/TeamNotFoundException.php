<?php

namespace App\Exception;

use Throwable;

class TeamNotFoundException extends \Exception
{
    public function __construct($message = 'Team not found', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
