<?php

namespace Unit\Application\Factory;

use App\Application\Factory\RequestHashFactory;
use App\Domain\ValueObject\RequestHash;
use PHPUnit\Framework\TestCase;

class RequestHashFactoryTest extends TestCase
{
    /**
     * @var RequestHashFactory
     */
    private $factory;

    public function setUp()
    {
        $this->factory = new RequestHashFactory();
    }

    /**
     * @test
     */
    public function shouldCreateRequestHash()
    {
        $requestBody = 'request-string';
        $requestHash = $this->factory->createFromRequest($requestBody);

        $this->assertInstanceOf(RequestHash::class, $requestHash);
        $this->assertEquals(md5($requestBody), (string) $requestHash);
    }
}
