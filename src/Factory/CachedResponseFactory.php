<?php

namespace App\Factory;

use App\Entity\CachedResponse;
use App\ValueObject\RequestHash;
use DateTimeImmutable;
use Psr\Http\Message\ResponseInterface;

class CachedResponseFactory
{
    public function createFromResponse(
        RequestHash $requestHash,
        ResponseInterface $response
    ): CachedResponse {
        $cachedResponse = new CachedResponse();
        $cachedResponse->setRequestHash($requestHash);
        $cachedResponse->setResponse($response);
        $cachedResponse->setCreatedAt(new DateTimeImmutable());
        return $cachedResponse;
    }
}
