<?php

namespace App\Factory;

use App\Application\Dto\UncachedResponse;
use Psr\Http\Message\ResponseInterface;

class UncachedResponseFactory
{
    public function createFromResponse(
        ResponseInterface $response
    ): UncachedResponse {
        return new UncachedResponse($response->getBody()->getContents());
    }
}
