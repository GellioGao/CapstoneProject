<?php

namespace App\Providers;

use App\Contracts\ILogger;

abstract class RecordableProvider
{
    protected ILogger $logger;
    public function __construct(ILogger $logger)
    {
        $this->logger = $logger;
    }
}
