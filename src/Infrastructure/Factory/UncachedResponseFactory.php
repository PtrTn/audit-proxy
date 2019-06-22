<?php

declare(strict_types=1);

namespace App\Infrastructure\Factory;

use App\Domain\ValueObject\RequestHash;
use App\Infrastructure\Entity\UncachedResponse;
use DateTimeImmutable;

class UncachedResponseFactory
{
    public function createFromResponse(
        RequestHash $requestHash,
        string $requestBody
    ) : UncachedResponse {
        $cachedResponse = new UncachedResponse();
        $cachedResponse->setRequestHash((string) $requestHash);
        $cachedResponse->setRequestBody($requestBody);
        $cachedResponse->setCreatedAt(new DateTimeImmutable());

        return $cachedResponse;
    }
}
