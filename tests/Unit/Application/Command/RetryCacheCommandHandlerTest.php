<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Command;

use App\Application\Command\RetryCacheCommand;
use App\Application\Command\RetryCacheCommandHandler;
use App\Application\Command\StoreCacheResponseCommandHandler;
use App\Application\Dto\UncachedResponse as UncachedResponseDto;
use App\Application\Query\FindUncachedResponseQueryHandler;
use App\Infrastructure\Entity\UncachedResponse as UncachedResponseEntity;
use App\Infrastructure\Repository\UncachedResponseRepository;
use PHPUnit\Framework\TestCase;

class RetryCacheCommandHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function shouldRetryIfUncached() : void
    {
        $response = $this->createMock(UncachedResponseEntity::class);
        $response->method('getRequestBody')->willReturn('some-request-body');

        $repository = $this->createMock(UncachedResponseRepository::class);
        $repository->method('findMostRecent')->willReturn([$response]);

        $uncachedResponse = $this->createMock(UncachedResponseDto::class);
        $queryHandler     = $this->createMock(FindUncachedResponseQueryHandler::class);
        $queryHandler->method('handle')->willReturn($uncachedResponse);

        $storeResponseCommandHandler = $this->createMock(StoreCacheResponseCommandHandler::class);
        $storeResponseCommandHandler->expects($this->once())->method('handle');

        $command = new RetryCacheCommand();
        $handler = new RetryCacheCommandHandler(
            $repository,
            $queryHandler,
            $storeResponseCommandHandler
        );

        $handler->handle($command);
    }

    /**
     * @test
     */
    public function shouldNotStoreIfNoResponse() : void
    {
        $response = $this->createMock(UncachedResponseEntity::class);
        $response->method('getRequestBody')->willReturn('some-request-body');

        $repository = $this->createMock(UncachedResponseRepository::class);
        $repository->method('findMostRecent')->willReturn([$response]);

        $queryHandler = $this->createMock(FindUncachedResponseQueryHandler::class);
        $queryHandler->method('handle')->willReturn(null);

        $storeResponseCommandHandler = $this->createMock(StoreCacheResponseCommandHandler::class);
        $storeResponseCommandHandler->expects($this->never())->method('handle');

        $command = new RetryCacheCommand();
        $handler = new RetryCacheCommandHandler(
            $repository,
            $queryHandler,
            $storeResponseCommandHandler
        );

        $handler->handle($command);
    }

    /**
     * @test
     */
    public function shouldNotRetryIfNoUncachedResponses() : void
    {
        $repository = $this->createMock(UncachedResponseRepository::class);
        $repository->method('findMostRecent')->willReturn([]);

        $queryHandler = $this->createMock(FindUncachedResponseQueryHandler::class);
        $queryHandler->expects($this->never())->method('handle');

        $storeResponseCommandHandler = $this->createMock(StoreCacheResponseCommandHandler::class);
        $storeResponseCommandHandler->expects($this->never())->method('handle');

        $command = new RetryCacheCommand();
        $handler = new RetryCacheCommandHandler(
            $repository,
            $queryHandler,
            $storeResponseCommandHandler
        );

        $handler->handle($command);
    }
}
