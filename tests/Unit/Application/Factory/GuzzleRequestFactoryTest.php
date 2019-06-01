<?php

namespace Unit\Application\Factory;

use App\Application\Factory\GuzzleRequestFactory;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;

class GuzzleRequestFactoryTest extends TestCase
{
    /**
     * @var GuzzleRequestFactory
     */
    private $factory;

    /**
     * @var string
     */
    private $registryUrl;

    public function setUp()
    {
        $this->registryUrl = 'http://some-registry-url.com';
        $this->factory = new GuzzleRequestFactory($this->registryUrl);
    }

    /**
     * @test
     */
    public function shouldCreateFromRequestBody()
    {
        $requestBody = 'request-string';
        $guzzleRequest = $this->factory->fromRequestBody($requestBody);

        $this->assertInstanceOf(Request::class, $guzzleRequest);
        $this->assertEquals('POST', $guzzleRequest->getMethod());
        $this->assertEquals(
            $this->registryUrl,
            $guzzleRequest->getUri()->getScheme() .
            '://' .
            $guzzleRequest->getUri()->getHost());
        $this->assertCount(3, $guzzleRequest->getHeaders());
        $this->assertEquals('application/json', $guzzleRequest->getHeaderLine('Content-Type'));
        $this->assertEquals('application/json', $guzzleRequest->getHeaderLine('Accept'));
    }
}
