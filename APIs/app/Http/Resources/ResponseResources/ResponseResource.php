<?php

namespace App\Http\Resources\ResponseResources;

use Illuminate\Http\Resources\Json\JsonResource;

use JsonSerializable;

abstract class ResponseResource extends JsonResource implements JsonSerializable
{
    private $result, $access, $message, $error;

    /**
     *  Use to wrap the response data, override it if want to use another one.
     * @var string
     */
    protected $dataWrapper = RESPONSE_FIELD_DATA;

    public function __construct($resource, $result, $access, $message, $error = null)
    {
        parent::__construct($resource);
        $this->result = $result;
        $this->access = $access;
        $this->message = $message;
        $this->error = $error;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $showAll = config('app.api_response_show_all_fields') === true;
        $data = $this->getData();
        return [
            RESPONSE_FIELD_RESULT => $this->result,
            RESPONSE_FIELD_ACCESS => $this->when($showAll || $this->access != ALLOWED_ACCESS_RESPONSE, $this->access),
            RESPONSE_FIELD_MESSAGE => $this->when($showAll || isset($this->message), $this->message),
            RESPONSE_FIELD_ERROR => $this->when($showAll || isset($this->error), $this->error),
            $this->dataWrapper => $this->when($showAll || !is_null($data), $data)
        ];
    }

    protected function getData(): ?array
    {
        return null;
    }
}
