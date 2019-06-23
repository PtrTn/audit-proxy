<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Command;

use App\Application\Command\StoreCacheResponseCommand;
use App\Application\Command\StoreCacheResponseCommandHandler;
use App\Application\Dto\UncachedResponse;
use App\Application\Factory\RequestHashFactory;
use App\Domain\ValueObject\RequestHash;
use App\Infrastructure\Entity\CachedResponse;
use App\Infrastructure\Factory\CachedResponseFactory;
use App\Infrastructure\Repository\CachedResponseRepository;
use App\Infrastructure\Repository\UncachedResponseRepository;
use PHPUnit\Framework\TestCase;

class StoreCacheResponseCommandHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function shouldStoreCachedResponse() : void
    {
        $hashFactory = $this->createMock(RequestHashFactory::class);
        $hashFactory->method('createFromRequest')->willReturn(new RequestHash('some-hash'));

        $responseRepository = $this->createMock(CachedResponseRepository::class);
        $responseRepository->expects($this->once())->method('save');

        $cachedResponse        = $this->createMock(CachedResponse::class);
        $cachedResponseFactory = $this->createMock(CachedResponseFactory::class);
        $cachedResponseFactory->method('createFromResponse')->willReturn($cachedResponse);

        $uncachedResponseRepository = $this->createMock(UncachedResponseRepository::class);
        $uncachedResponseRepository->method('deleteForHash');

        $uncachedResponse = $this->createMock(UncachedResponse::class);
        $command          = new StoreCacheResponseCommand('some-request-body', $uncachedResponse);
        $handler          = new StoreCacheResponseCommandHandler(
            $hashFactory,
            $responseRepository,
            $cachedResponseFactory,
            $uncachedResponseRepository
        );
        $handler->handle($command);
    }

    /**
     * @test
     */
    public function shouldRemoveUncachedResponseAfterCaching() : void
    {
        $hashFactory = $this->createMock(RequestHashFactory::class);
        $hashFactory->method('createFromRequest')->willReturn(new RequestHash('some-hash'));

        $responseRepository = $this->createMock(CachedResponseRepository::class);
        $responseRepository->method('save');

        $cachedResponse        = $this->createMock(CachedResponse::class);
        $cachedResponseFactory = $this->createMock(CachedResponseFactory::class);
        $cachedResponseFactory->method('createFromResponse')->willReturn($cachedResponse);

        $uncachedResponseRepository = $this->createMock(UncachedResponseRepository::class);
        $uncachedResponseRepository->expects($this->once())->method('deleteForHash');

        $uncachedResponse = $this->createMock(UncachedResponse::class);
        $command          = new StoreCacheResponseCommand('some-request-body', $uncachedResponse);
        $handler          = new StoreCacheResponseCommandHandler(
            $hashFactory,
            $responseRepository,
            $cachedResponseFactory,
            $uncachedResponseRepository
        );
        $handler->handle($command);
    }
}
