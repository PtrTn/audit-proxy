<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Tests\Helpers\FixtureAware;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestCase;

class YarnProxyAuditRequestTest extends TestCase
{
    use FixtureAware;

    /** @var Client */
    private $client;

    public function setUp() : void
    {
        $this->client = new Client();
    }

    public function testWithGzippedBody() : void
    {
        $request  = new Request(
            'POST',
            new Uri('http://audit-proxy.test/-/npm/v1/security/audits'),
            $this->getContentsFromJson('headers-yarn.json'),
            $this->getFixtureResource('request-body-yarn.gz')
        );
        $response = $this->client->send($request);

        $this->assertEquals(200, $response->getStatusCode(), 'Expected response to return a 200');
        $this->assertJson($response->getBody()->getContents(), 'Expected response to be valid Json');
    }
}
