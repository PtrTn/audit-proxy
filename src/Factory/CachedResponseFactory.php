<?php

namespace App\Factory;

use App\Application\Dto\UncachedResponse;
use App\Infrastructure\Entity\CachedResponse;
use App\Domain\ValueObject\RequestHash;
use DateTimeImmutable;

class CachedResponseFactory
{
    public function createFromResponse(
        RequestHash $requestHash,
        string $requestBody,
        UncachedResponse $response
    ): CachedResponse {
        $cachedResponse = new CachedResponse();
        $cachedResponse->setRequestHash($requestHash);
        $cachedResponse->setRequestBody($requestBody);
        $cachedResponse->setResponse($response->getResponse());
        $cachedResponse->setCreatedAt(new DateTimeImmutable());
        $cachedResponse->setUpdatedAt(new DateTimeImmutable());
        return $cachedResponse;
    }
}
