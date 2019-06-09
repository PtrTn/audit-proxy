<?php

namespace Unit\Application\Factory;

use App\Application\Dto\UncachedResponse;
use App\Application\Factory\UncachedResponseFactory;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class UncachedResponseFactoryTest extends TestCase
{
    /**
     * @var UncachedResponseFactory
     */
    private $factory;

    public function setUp()
    {
        $this->factory = new UncachedResponseFactory();
    }

    /**
     * @test
     */
    public function shouldCreateFromResponse()
    {
        $requestBody = 'request-string';
        $response = new Response(200, [], $requestBody);
        $uncachedResponse = $this->factory->createFromResponse($response);

        $this->assertInstanceOf(UncachedResponse::class, $uncachedResponse);
        $this->assertEquals($requestBody, $uncachedResponse->getResponse());
    }
}
