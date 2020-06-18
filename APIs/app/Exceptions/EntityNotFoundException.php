<?php

namespace App\Exceptions;

use Throwable;

class EntityNotFoundException extends CustomException implements Throwable
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
