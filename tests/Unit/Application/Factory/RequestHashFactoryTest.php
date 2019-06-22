<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Factory;

use App\Application\Factory\RequestHashFactory;
use App\Domain\ValueObject\RequestHash;
use PHPUnit\Framework\TestCase;
use function md5;

class RequestHashFactoryTest extends TestCase
{
    /** @var RequestHashFactory */
    private $factory;

    public function setUp() : void
    {
        $this->factory = new RequestHashFactory();
    }

    /**
     * @test
     */
    public function shouldCreateRequestHash() : void
    {
        $requestBody = 'request-string';
        $requestHash = $this->factory->createFromRequest($requestBody);

        $this->assertInstanceOf(RequestHash::class, $requestHash);
        $this->assertEquals(md5($requestBody), (string) $requestHash);
    }
}
