<?php

namespace App\Http\Resources\ResponseResources;

use JsonSerializable;

class BadResponseResource extends ResponseResource implements JsonSerializable
{
    public function __construct($result, $access, $message, $error)
    {
        parent::__construct(null, $result, $access, $message, $error);
    }
}
