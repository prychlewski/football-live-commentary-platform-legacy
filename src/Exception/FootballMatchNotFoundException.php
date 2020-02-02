<?php

namespace App\Exception;

use Throwable;

class FootballMatchNotFoundException extends \Exception
{
    public function __construct($message = 'Football match not found', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
