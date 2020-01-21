<?php

namespace App\Exception;

use Throwable;

class EventCommentNotFoundException extends \Exception
{
    public function __construct($message = 'Event comment not found', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
