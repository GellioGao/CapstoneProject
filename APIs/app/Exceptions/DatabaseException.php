<?php

namespace App\Exceptions;

use Throwable;

class DatabaseException extends CustomException implements Throwable
{
    public function __construct($ex)
    {
        parent::__construct($ex);
    }
}