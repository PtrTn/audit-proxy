<?php

declare(strict_types=1);

namespace App\Application\Factory;

use App\Application\Dto\UncachedResponse;
use Psr\Http\Message\ResponseInterface;

class UncachedResponseFactory
{
    public function createFromResponse(
        ResponseInterface $response
    ) : UncachedResponse {
        return new UncachedResponse($response->getBody()->getContents());
    }
}
