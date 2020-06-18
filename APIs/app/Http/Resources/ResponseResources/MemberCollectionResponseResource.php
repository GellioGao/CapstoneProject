<?php

namespace App\Http\Resources\ResponseResources;

use App\Http\Resources\MemberResource;

use JsonSerializable;

class MemberCollectionResponseResource extends ResponseResource implements JsonSerializable
{
    protected $dataWrapper = RESPONSE_FIELD_DATA_MEMBERS;

    public function __construct($resource, $message = null)
    {
        parent::__construct($resource, SUCCESS_RESULT_RESPONSE, ALLOWED_ACCESS_RESPONSE, $message);
    }

    protected function getData(): ?array
    {
        return MemberResource::collection($this->resource)->jsonSerialize();
    }
}
