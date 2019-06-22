<?php

namespace App\Tests\Unit\Application\Command;

use App\Application\Command\CleanupCacheCommand;
use App\Application\Command\CleanupCacheCommandHandler;
use App\Infrastructure\Entity\CachedResponse;
use App\Infrastructure\Repository\CachedResponseRepository;
use PHPUnit\Framework\TestCase;

class CleanupCacheCommandHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCleanupUnusedCache()
    {
        $response = $this->createMock(CachedResponse::class);

        $repository = $this->createMock(CachedResponseRepository::class);
        $repository->method('findUnused')->willReturn([$response]);
        $repository->expects($this->once())->method('delete');

        $command = new CleanupCacheCommand();
        $handler = new CleanupCacheCommandHandler($repository);

        $handler->handle($command);
    }

    /**
     * @test
     */
    public function shouldNotCleanupCacheIfNoUnused()
    {
        $repository = $this->createMock(CachedResponseRepository::class);
        $repository->method('findUnused')->willReturn([]);
        $repository->expects($this->never())->method('delete');

        $command = new CleanupCacheCommand();
        $handler = new CleanupCacheCommandHandler($repository);

        $handler->handle($command);
    }
}
