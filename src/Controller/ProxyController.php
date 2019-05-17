<?php

namespace App\Controller;

use App\Command\StoreResponseCommand;
use App\Command\StoreResponseCommandHandler;
use App\Query\FindCachedResponseQuery;
use App\Query\FindCachedResponseQueryHandler;
use App\Query\FindUncachedResponseQuery;
use App\Query\FindUncachedResponseQueryHandler;
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

    public function __construct(
        FindCachedResponseQueryHandler $cachedResponseQueryHandler,
        FindUncachedResponseQueryHandler $uncachedResponseQueryHandler,
        StoreResponseCommandHandler $responseCommandHandler
    ){
        $this->cachedResponseQueryHandler = $cachedResponseQueryHandler;
        $this->uncachedResponseQueryHandler = $uncachedResponseQueryHandler;
        $this->responseCommandHandler = $responseCommandHandler;
    }

    public function index(Request $request): Response
    {
        $cachedResponse = $this->cachedResponseQueryHandler->handle(new FindCachedResponseQuery($request));
        if ($cachedResponse !== null) {
            return $cachedResponse->toSymfonyResponse();
        }

        $uncachedResponse = $this->uncachedResponseQueryHandler->handle(new FindUncachedResponseQuery($request));
        if ($uncachedResponse === null) {
            return new JsonResponse(['error' => 'Registry error'], 503);
        }

        $this->responseCommandHandler->handle(new StoreResponseCommand($request, $uncachedResponse));

        return $uncachedResponse->toSymfonyResponse();
    }
}
