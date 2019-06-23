<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Application\Dto\UncachedResponse;

class StoreCacheResponseCommand
{
    /** @var string */
    private $requestBody;

    /** @var UncachedResponse */
    private $response;

    public function __construct(string $requestBody, UncachedResponse $response)
    {
        $this->requestBody = $requestBody;
        $this->response    = $response;
    }

    public function getRequestBody() : string
    {
        return $this->requestBody;
    }

    public function getResponse() : UncachedResponse
    {
        return $this->response;
    }
}
