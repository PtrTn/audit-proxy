<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Infrastructure\Repository\CachedResponseRepository;

class CleanupCacheCommandHandler
{
    /** @var CachedResponseRepository */
    private $repository;

    public function __construct(CachedResponseRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(CleanupCacheCommand $command) : void
    {
        $cachedResponses = $this->repository->findUnused();
        foreach ($cachedResponses as $cachedResponse) {
            $this->repository->delete($cachedResponse);
        }
    }
}
