<?php

namespace App\Exceptions;

use Throwable;

class TokenMissingException extends CustomException implements Throwable
{
    public function __construct()
    {
        parent::__construct(TOKEN_MISSING_EXCEPTION_MESSAGE);
    }
}
