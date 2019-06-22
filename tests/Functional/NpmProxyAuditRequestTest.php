<?php

namespace App\Tests\Functional;

use App\Tests\Helpers\FixtureAwareTrait;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestCase;

class NpmProxyAuditRequestTest extends TestCase
{
    use FixtureAwareTrait;

    /**
     * @var Client
     */
    private $client;

    public function setUp(): void
    {
        $this->client = new Client();
    }

    public function testWithGzippedBody(): void
    {
        $request = new Request(
            'POST',
            new Uri('http://audit-proxy.test/-/npm/v1/security/audits'),
            $this->getContentsFromJson('headers-npm.json'),
            $this->getFixtureResource('request-body-npm.gz')
        );
        $response = $this->client->send($request);

        $this->assertEquals(200, $response->getStatusCode(), 'Expected response to return a 200');
        $this->assertJson($response->getBody()->getContents(), 'Expected response to be valid Json');
    }
}
