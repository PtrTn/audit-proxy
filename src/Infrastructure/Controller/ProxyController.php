<?php

namespace App\Infrastructure\Controller;

use App\Application\Command\StoreCacheRequestCommand;
use App\Application\Command\StoreCacheRequestCommandHandler;
use App\Application\Command\StoreCacheResponseCommand;
use App\Application\Command\StoreCacheResponseCommandHandler;
use App\Application\Command\UpdateCacheLastHitCommand;
use App\Application\Command\UpdateCacheLastHitCommandHandler;
use App\Application\Decode\GzipDecoder;
use App\Application\Query\FindCachedResponseQuery;
use App\Application\Query\FindCachedResponseQueryHandler;
use App\Application\Query\FindUncachedResponseQuery;
use App\Application\Query\FindUncachedResponseQueryHandler;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProxyController
{
    /**
     * @var FindCachedResponseQueryHandler
     */
    private $cachedResponseQueryHandler;

    /**
     * @var FindUncachedResponseQueryHandler
     */
    private $uncachedResponseQueryHandler;

    /**
     * @var StoreCacheResponseCommandHandler
     */
    private $storeResponseCommandHandler;

    /**
     * @var StoreCacheRequestCommandHandler
     */
    private $storeRequestCommandHandler;

    /**
     * @var UpdateCacheLastHitCommandHandler
     */
    private $lastHitCommandHandler;

    /**
     * @var GzipDecoder
     */
    private $gzipDecoder;

    public function __construct(
        FindCachedResponseQueryHandler $cachedResponseQueryHandler,
        FindUncachedResponseQueryHandler $uncachedResponseQueryHandler,
        StoreCacheResponseCommandHandler $storeResponseCommandHandler,
        StoreCacheRequestCommandHandler $storeRequestCommandHandler,
        UpdateCacheLastHitCommandHandler $lastHitCommandHandler,
        GzipDecoder $gzipDecoder
    ){
        $this->cachedResponseQueryHandler = $cachedResponseQueryHandler;
        $this->uncachedResponseQueryHandler = $uncachedResponseQueryHandler;
        $this->storeResponseCommandHandler = $storeResponseCommandHandler;
        $this->storeRequestCommandHandler = $storeRequestCommandHandler;
        $this->lastHitCommandHandler = $lastHitCommandHandler;
        $this->gzipDecoder = $gzipDecoder;
    }

    public function index(Request $request): Response
    {
        // Todo, optionally decouple cached response entity and dto.

        $requestBody = $this->gzipDecoder->decode($request->getContent());
        $cachedResponse = $this->cachedResponseQueryHandler->handle(new FindCachedResponseQuery($requestBody));
        if ($cachedResponse !== null) {
            $this->lastHitCommandHandler->handle(new UpdateCacheLastHitCommand(
                $cachedResponse->getId(),
                new DateTimeImmutable())
            );
            return $cachedResponse->toSymfonyResponse();
        }

        $uncachedResponse = $this->uncachedResponseQueryHandler->handle(new FindUncachedResponseQuery($requestBody));
        if ($uncachedResponse === null) {
            $this->storeRequestCommandHandler->handle(new StoreCacheRequestCommand($requestBody));
            return new JsonResponse(['error' => 'Registry error'], 503);
        }

        $this->storeResponseCommandHandler->handle(new StoreCacheResponseCommand($requestBody, $uncachedResponse));

        return $uncachedResponse->toSymfonyResponse();
    }
}
