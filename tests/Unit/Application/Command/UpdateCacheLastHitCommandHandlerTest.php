<?php

namespace Unit\Application\Query;

use App\Application\Command\UpdateCacheLastHitCommand;
use App\Application\Command\UpdateCacheLastHitCommandHandler;
use App\Infrastructure\Entity\CachedResponse;
use App\Infrastructure\Repository\CachedResponseRepository;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class UpdateCacheLastHitCommandHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function shouldUpdateLastHit()
    {
        $cachedResponse = $this->createMock(CachedResponse::class);
        $cachedResponse->method('setLastCacheHitAt');

        $repository = $this->createMock(CachedResponseRepository::class);
        $repository->method('findOneBy')->willReturn($cachedResponse);
        $repository->expects($this->once())->method('save');

        $logger = $this->createMock(LoggerInterface::class);

        $command = new UpdateCacheLastHitCommand(123, new \DateTimeImmutable('today'));
        $handler = new UpdateCacheLastHitCommandHandler($repository, $logger);
        $handler->handle($command);
    }

    /**
     * @test
     */
    public function shouldLogIfNoResponseToUpdate()
    {
        $repository = $this->createMock(CachedResponseRepository::class);
        $repository->method('findOneBy')->willReturn(null);
        $repository->expects($this->never())->method('save');

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('warning');

        $command = new UpdateCacheLastHitCommand(123, new \DateTimeImmutable('today'));
        $handler = new UpdateCacheLastHitCommandHandler($repository, $logger);
        $handler->handle($command);
    }
}
