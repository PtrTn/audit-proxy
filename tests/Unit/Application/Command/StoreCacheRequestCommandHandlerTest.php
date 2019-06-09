<?php

namespace Unit\Application\Query;

use App\Application\Command\StoreCacheRequestCommand;
use App\Application\Command\StoreCacheRequestCommandHandler;
use App\Application\Factory\RequestHashFactory;
use App\Domain\ValueObject\RequestHash;
use App\Infrastructure\Entity\UncachedResponse;
use App\Infrastructure\Factory\UncachedResponseFactory;
use App\Infrastructure\Repository\UncachedResponseRepository;
use PHPUnit\Framework\TestCase;

class StoreCacheRequestCommandHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function shouldStoreRequestCache()
    {
        $hashFactory = $this->createMock(RequestHashFactory::class);
        $hashFactory->method('createFromRequest')->willReturn(new RequestHash('some-hash'));

        $repository = $this->createMock(UncachedResponseRepository::class);
        $repository->method('hasRequestWithHash')->willReturn(false);
        $repository->expects($this->once())->method('save');

        $uncachedResponse = $this->createMock(UncachedResponse::class);
        $responseFactory = $this->createMock(UncachedResponseFactory::class);
        $responseFactory->method('createFromResponse')->willReturn($uncachedResponse);

        $command = new StoreCacheRequestCommand('some-request-body');
        $handler = new StoreCacheRequestCommandHandler(
            $hashFactory,
            $repository,
            $responseFactory
        );
        $handler->handle($command);
    }

    /**
     * @test
     */
    public function shouldNotStoreIfAlreadyCached()
    {
        $hashFactory = $this->createMock(RequestHashFactory::class);
        $hashFactory->method('createFromRequest')->willReturn(new RequestHash('some-hash'));

        $repository = $this->createMock(UncachedResponseRepository::class);
        $repository->method('hasRequestWithHash')->willReturn(true);
        $repository->expects($this->never())->method('save');

        $responseFactory = $this->createMock(UncachedResponseFactory::class);

        $command = new StoreCacheRequestCommand('some-request-body');
        $handler = new StoreCacheRequestCommandHandler(
            $hashFactory,
            $repository,
            $responseFactory
        );
        $handler->handle($command);
    }
}
