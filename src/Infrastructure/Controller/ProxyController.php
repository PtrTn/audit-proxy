<?php

namespace App\Infrastructure\Controller;

use App\Application\Command\StoreResponseCommand;
use App\Application\Command\StoreResponseCommandHandler;
use App\Application\Decode\GzipDecoder;
use App\Application\Query\FindCachedResponseQuery;
use App\Application\Query\FindCachedResponseQueryHandler;
use App\Application\Query\FindUncachedResponseQuery;
use App\Application\Query\FindUncachedResponseQueryHandler;
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
     * @var StoreResponseCommandHandler
     */
    private $responseCommandHandler;

    /**
     * @var GzipDecoder
     */
    private $gzipDecoder;

    public function __construct(
        FindCachedResponseQueryHandler $cachedResponseQueryHandler,
        FindUncachedResponseQueryHandler $uncachedResponseQueryHandler,
        StoreResponseCommandHandler $responseCommandHandler,
        GzipDecoder $gzipDecoder
    ){
        $this->cachedResponseQueryHandler = $cachedResponseQueryHandler;
        $this->uncachedResponseQueryHandler = $uncachedResponseQueryHandler;
        $this->responseCommandHandler = $responseCommandHandler;
        $this->gzipDecoder = $gzipDecoder;
    }

    public function index(Request $request): Response
    {
        // Todo, refresh cached data in background.
        // Todo, add refresh job for 503 uncached responses.
        // Todo, optionally decouple cached response entity and dto.

        $requestBody = $this->gzipDecoder->decode($request->getContent());
        $cachedResponse = $this->cachedResponseQueryHandler->handle(new FindCachedResponseQuery($requestBody));
        if ($cachedResponse !== null) {
            return $cachedResponse->toSymfonyResponse();
        }

        $uncachedResponse = $this->uncachedResponseQueryHandler->handle(new FindUncachedResponseQuery($requestBody));
        if ($uncachedResponse === null) {
            return new JsonResponse(['error' => 'Registry error'], 503);
        }

        $this->responseCommandHandler->handle(new StoreResponseCommand($requestBody, $uncachedResponse));

        return $uncachedResponse->toSymfonyResponse();
    }
}
