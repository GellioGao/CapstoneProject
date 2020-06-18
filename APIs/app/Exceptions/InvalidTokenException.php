<?php

namespace App\Exceptions;

use Throwable;

class InvalidTokenException extends CustomException implements Throwable
{
    public function __construct($message = null)
    {
        if(!isset($message)){
            $message = INVALID_TOKEN_EXCEPTION_MESSAGE;
        }
        parent::__construct($message);
    }
}
