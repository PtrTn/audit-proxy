<?php

namespace App\Application\Command;

use App\Application\Query\FindUncachedResponseQuery;
use App\Application\Query\FindUncachedResponseQueryHandler;
use App\Infrastructure\Repository\UncachedResponseRepository;

class RetryCacheCommandHandler
{
    /**
     * @var UncachedResponseRepository
     */
    private $repository;

    /**
     * @var FindUncachedResponseQueryHandler
     */
    private $queryHandler;

    /**
     * @var StoreCacheResponseCommandHandler
     */
    private $storeResponseCommandHandler;

    public function __construct(
        UncachedResponseRepository $repository,
        FindUncachedResponseQueryHandler $queryHandler,
        StoreCacheResponseCommandHandler $storeResponseCommandHandler
    ) {
        $this->repository = $repository;
        $this->queryHandler = $queryHandler;
        $this->storeResponseCommandHandler = $storeResponseCommandHandler;
    }

    public function handle(RetryCacheCommand $command): void
    {
        $uncachedResponses = $this->repository->findMostRecent();
        foreach ($uncachedResponses as $uncachedResponse) {
            $response = $this->queryHandler->handle(new FindUncachedResponseQuery($uncachedResponse->getRequestBody()));
            if ($response === null) {
                continue;
            }
            $this->storeResponseCommandHandler->handle(new StoreCacheResponseCommand($uncachedResponse->getRequestBody(), $response));
        }
    }
}
