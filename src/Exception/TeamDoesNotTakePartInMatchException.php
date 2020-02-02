<?php

namespace App\Exception;

use Throwable;

class TeamDoesNotTakePartInMatchException extends \Exception
{
    public function __construct(
        $message = 'Team does not take part in that match',
        $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
