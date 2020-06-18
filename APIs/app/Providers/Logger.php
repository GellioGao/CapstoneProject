<?php

namespace App\Providers;

use App\Contracts\ILogger;

use Illuminate\Support\Facades\Log;

class Logger implements ILogger
{
    public function emergency($message)
    {
        Log::emergency($message);
    }
    public function alert($message)
    {
        Log::alert($message);
    }
    public function critical($message)
    {
        Log::critical($message);
    }
    public function error($message)
    {
        Log::error($message);
    }
    public function warning($message)
    {
        Log::warning($message);
    }
    public function notice($message)
    {
        Log::notice($message);
    }
    public function info($message)
    {
        Log::info($message);
    }
    public function debug($message)
    {
        Log::debug($message);
    }
}
