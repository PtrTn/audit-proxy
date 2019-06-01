<?php

namespace App\Application\Command;

use App\Application\Query\FindUncachedResponseQueryHandler;
use App\Infrastructure\Repository\CachedResponseRepository;

class CleanupCacheCommandHandler
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

    public function handle(CleanupCacheCommand $command): void
    {
        $cachedResponses = $this->repository->findUnused();
        foreach ($cachedResponses as $cachedResponse) {
            $this->repository->delete($cachedResponse);
        }
    }
}
