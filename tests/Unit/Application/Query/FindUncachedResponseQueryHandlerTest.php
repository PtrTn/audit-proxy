<?php

namespace App\Tests\Unit\Application\Query;

use App\Application\Dto\UncachedResponse;
use App\Application\Factory\GuzzleRequestFactory;
use App\Application\Factory\UncachedResponseFactory;
use App\Application\Query\FindUncachedResponseQuery;
use App\Application\Query\FindUncachedResponseQueryHandler;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class FindUncachedResponseQueryHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnIfResponse()
    {
        $request = $this->createMock(Request::class);
        $response = $this->createMock(ResponseInterface::class);
        $uncachedResponse = $this->createMock(UncachedResponse::class);

        $mapper = $this->createMock(GuzzleRequestFactory::class);
        $mapper->method('fromRequestBody')->willReturn($request);

        $client = $this->createMock(Client::class);
        $client->method('send')->willReturn($response);

        $factory = $this->createMock(UncachedResponseFactory::class);
        $factory->method('createFromResponse')->willReturn($uncachedResponse);

        $logger = $this->createMock(LoggerInterface::class);

        $query = new FindUncachedResponseQuery('request-body');
        $handler = new FindUncachedResponseQueryHandler($mapper, $client, $factory, $logger);

        $actualResponse = $handler->handle($query);
        $this->assertEquals($uncachedResponse, $actualResponse);
    }

    /**
     * @test
     */
    public function shouldReturnNullAndLogIfError()
    {
        $request = $this->createMock(Request::class);
        $response = $this->createMock(ResponseInterface::class);

        $mapper = $this->createMock(GuzzleRequestFactory::class);
        $mapper->method('fromRequestBody')->willReturn($request);

        $exception = new ServerException('something-went-wrong', $request, $response);

        $client = $this->createMock(Client::class);
        $client->method('send')->willThrowException($exception);

        $factory = $this->createMock(UncachedResponseFactory::class);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('warning');

        $query = new FindUncachedResponseQuery('request-body');
        $handler = new FindUncachedResponseQueryHandler($mapper, $client, $factory, $logger);

        $actualResponse = $handler->handle($query);
        $this->assertNull($actualResponse);
    }
}
