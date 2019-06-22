<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Command;

use App\Application\Command\RefreshCacheCommand;
use App\Application\Command\RefreshCacheCommandHandler;
use App\Application\Dto\UncachedResponse;
use App\Application\Query\FindUncachedResponseQueryHandler;
use App\Infrastructure\Entity\CachedResponse;
use App\Infrastructure\Repository\CachedResponseRepository;
use PHPUnit\Framework\TestCase;

class RefreshCacheCommandHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function shouldRefreshOutdatedCache() : void
    {
        $response = $this->createMock(CachedResponse::class);

        $repository = $this->createMock(CachedResponseRepository::class);
        $repository->method('findMostOutdated')->willReturn([$response]);
        $repository->expects($this->once())->method('save');

        $uncachedResponse = $this->createMock(UncachedResponse::class);

        $queryHandler = $this->createMock(FindUncachedResponseQueryHandler::class);
        $queryHandler->expects($this->once())->method('handle')->willReturn($uncachedResponse);

        $command = new RefreshCacheCommand();
        $handler = new RefreshCacheCommandHandler($repository, $queryHandler);

        $handler->handle($command);
    }

    /**
     * @test
     */
    public function shouldNotUpdateCacheIfNoResponse() : void
    {
        $response = $this->createMock(CachedResponse::class);

        $repository = $this->createMock(CachedResponseRepository::class);
        $repository->method('findMostOutdated')->willReturn([$response]);
        $repository->expects($this->never())->method('save');

        $queryHandler = $this->createMock(FindUncachedResponseQueryHandler::class);
        $queryHandler->expects($this->once())->method('handle')->willReturn(null);

        $command = new RefreshCacheCommand();
        $handler = new RefreshCacheCommandHandler($repository, $queryHandler);

        $handler->handle($command);
    }

    /**
     * @test
     */
    public function shouldNotRefreshIfNoOutdatedCache() : void
    {
        $response = $this->createMock(CachedResponse::class);

        $repository = $this->createMock(CachedResponseRepository::class);
        $repository->method('findMostOutdated')->willReturn([$response]);
        $repository->expects($this->never())->method('save');

        $queryHandler = $this->createMock(FindUncachedResponseQueryHandler::class);
        $queryHandler->expects($this->once())->method('handle')->willReturn(null);

        $command = new RefreshCacheCommand();
        $handler = new RefreshCacheCommandHandler($repository, $queryHandler);

        $handler->handle($command);
    }
}
