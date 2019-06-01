<?php

namespace App\Application\Command;

use App\Application\Query\FindUncachedResponseQuery;
use App\Application\Query\FindUncachedResponseQueryHandler;
use App\Infrastructure\Repository\CachedResponseRepository;
use DateTimeImmutable;

class RefreshCacheCommandHandler
{

    /**
     * @var CachedResponseRepository
     */
    private $repository;

    /**
     * @var FindUncachedResponseQueryHandler
     */
    private $queryHandler;

    public function __construct(
        CachedResponseRepository $repository,
        FindUncachedResponseQueryHandler $queryHandler
    ) {
        $this->repository = $repository;
        $this->queryHandler = $queryHandler;
    }

    public function handle(RefreshCacheCommand $command): void
    {
        $cachedResponses = $this->repository->findAll();
        foreach ($cachedResponses as $cachedResponse) {
            $response = $this->queryHandler->handle(new FindUncachedResponseQuery($cachedResponse->getRequestBody()));
            if ($response === null) {
                continue;
            }
            $cachedResponse->setUpdatedAt(new DateTimeImmutable());
            $cachedResponse->setResponse($response->getResponse());
            $this->repository->save($cachedResponse);
        }
    }
}
