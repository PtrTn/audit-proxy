<?php

namespace App\Command;

use App\Dto\UncachedResponse;

class StoreResponseCommand
{
    /**
     * @var string
     */
    private $requestBody;

    /**
     * @var UncachedResponse
     */
    private $response;

    public function __construct(string $requestBody, UncachedResponse $response)
    {
        $this->requestBody = $requestBody;
        $this->response = $response;
    }

    public function getRequestBody(): string
    {
        return $this->requestBody;
    }

    public function getResponse(): UncachedResponse
    {
        return $this->response;
    }

}
