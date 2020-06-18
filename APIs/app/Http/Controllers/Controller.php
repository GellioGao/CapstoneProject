<?php

namespace App\Http\Controllers;

use App\Contracts\ILogger;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    //
    protected ILogger $logger;

    public function __construct(ILogger $logger)
    {
        $this->logger = $logger;
    }
}
