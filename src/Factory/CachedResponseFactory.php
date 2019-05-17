<?php

namespace App\Factory;

use App\Dto\UncachedResponse;
use App\Entity\CachedResponse;
use App\ValueObject\RequestHash;
use DateTimeImmutable;

class CachedResponseFactory
{
    public function createFromResponse(
        RequestHash $requestHash,
        UncachedResponse $response
    ): CachedResponse {
        $cachedResponse = new CachedResponse();
        $cachedResponse->setRequestHash($requestHash);
        $cachedResponse->setResponse($response->getResponse());
        $cachedResponse->setCreatedAt(new DateTimeImmutable());
        return $cachedResponse;
    }
}
