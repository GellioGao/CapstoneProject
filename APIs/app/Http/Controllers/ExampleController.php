<?php

namespace App\Http\Controllers;

use App\Contracts\ILogger;

class ExampleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ILogger $logger)
    {
        parent::__construct($logger);
        //
    }

    //
}
