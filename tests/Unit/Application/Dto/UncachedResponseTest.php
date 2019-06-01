<?php

namespace Unit\Application\Dto;

use App\Application\Dto\UncachedResponse;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class UncachedResponseTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeConvertableToSymfonyResponse()
    {
        $body = 'response-string';
        $dto = new UncachedResponse($body);
        $response = $dto->toSymfonyResponse();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals($body, $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('MISS', $response->headers->get('X-Cache'));
    }
}
