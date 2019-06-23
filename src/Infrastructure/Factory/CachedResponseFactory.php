<?php

declare(strict_types=1);

namespace App\Infrastructure\Factory;

use App\Application\Dto\UncachedResponse;
use App\Domain\ValueObject\RequestHash;
use App\Infrastructure\Entity\CachedResponse;
use DateTimeImmutable;

class CachedResponseFactory
{
    public function createFromResponse(
        RequestHash $requestHash,
        string $requestBody,
        UncachedResponse $response
    ) : CachedResponse {
        $cachedResponse = new CachedResponse();
        $cachedResponse->setRequestHash((string) $requestHash);
        $cachedResponse->setRequestBody($requestBody);
        $cachedResponse->setResponse($response->getResponse());
        $cachedResponse->setCreatedAt(new DateTimeImmutable());
        $cachedResponse->setUpdatedAt(new DateTimeImmutable());
        $cachedResponse->setLastCacheHitAt(new DateTimeImmutable());

        return $cachedResponse;
    }
}
