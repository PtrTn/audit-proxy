<?php

namespace Unit\Application\Query;

use App\Application\Factory\RequestHashFactory;
use App\Application\Query\FindCachedResponseQuery;
use App\Application\Query\FindCachedResponseQueryHandler;
use App\Domain\ValueObject\RequestHash;
use App\Infrastructure\Entity\CachedResponse;
use App\Infrastructure\Repository\CachedResponseRepository;
use PHPUnit\Framework\TestCase;

class FindCachedResponseQueryHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnNullfNoCache()
    {
        $hashFactory = $this->createMock(RequestHashFactory::class);
        $hashFactory->method('createFromRequest')->willReturn(new RequestHash('some-hash'));

        $repository = $this->createMock(CachedResponseRepository::class);
        $repository->method('findByRequestHash')->willReturn(null);

        $query = new FindCachedResponseQuery('some-request-body');
        $handler = new FindCachedResponseQueryHandler($hashFactory, $repository);

        $response = $handler->handle($query);

        $this->assertNull($response);
    }

    /**
     * @test
     */
    public function shouldReturnNullfInvalidCache()
    {
        $hashFactory = $this->createMock(RequestHashFactory::class);
        $hashFactory->method('createFromRequest')->willReturn(new RequestHash('some-hash'));

        $invalidResponse = $this->createMock(CachedResponse::class);
        $invalidResponse->method('isValid')->willReturn(false);

        $repository = $this->createMock(CachedResponseRepository::class);
        $repository->method('findByRequestHash')->willReturn($invalidResponse);

        $query = new FindCachedResponseQuery('some-request-body');
        $handler = new FindCachedResponseQueryHandler($hashFactory, $repository);

        $response = $handler->handle($query);

        $this->assertNull($response);
    }

    /**
     * @test
     */
    public function shouldReturnValidCache()
    {
        $hashFactory = $this->createMock(RequestHashFactory::class);
        $hashFactory->method('createFromRequest')->willReturn(new RequestHash('some-hash'));

        $validResponse = $this->createMock(CachedResponse::class);
        $validResponse->method('isValid')->willReturn(true);

        $repository = $this->createMock(CachedResponseRepository::class);
        $repository->method('findByRequestHash')->willReturn($validResponse);

        $query = new FindCachedResponseQuery('some-request-body');
        $handler = new FindCachedResponseQueryHandler($hashFactory, $repository);

        $response = $handler->handle($query);

        $this->assertEquals($validResponse, $response);
    }
}
