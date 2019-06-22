<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Factory;

use App\Application\Dto\UncachedResponse;
use App\Application\Factory\UncachedResponseFactory;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class UncachedResponseFactoryTest extends TestCase
{
    /** @var UncachedResponseFactory */
    private $factory;

    public function setUp() : void
    {
        $this->factory = new UncachedResponseFactory();
    }

    /**
     * @test
     */
    public function shouldCreateFromResponse() : void
    {
        $requestBody      = 'request-string';
        $response         = new Response(200, [], $requestBody);
        $uncachedResponse = $this->factory->createFromResponse($response);

        $this->assertInstanceOf(UncachedResponse::class, $uncachedResponse);
        $this->assertEquals($requestBody, $uncachedResponse->getResponse());
    }
}
