<?php

namespace Unit\Application\Decode;

use App\Domain\ValueObject\RequestHash;
use PHPUnit\Framework\TestCase;

class RequestHashTest extends TestCase
{
    /**
     * @test
     */
    public function shouldConvertToString()
    {
        $hash = md5('some-request-body');
        $requestHash = new RequestHash($hash);

        $this->assertEquals($hash, (string) $requestHash);
    }

    /**
     * @test
     */
    public function shouldBeEqualForSameHash()
    {
        $hash1 = md5('some-request-body');
        $requestHash1 = new RequestHash($hash1);

        $hash2 = $hash1;
        $requestHash2 = new RequestHash($hash2);

        $this->assertTrue($requestHash1->equals($requestHash2));
        $this->assertTrue($requestHash2->equals($requestHash1));
    }

    /**
     * @test
     */
    public function shouldNotBeEqualIfHashesDiffer()
    {
        $hash1 = md5('some-request-body');
        $requestHash1 = new RequestHash($hash1);

        $hash2 = md5('some-other-request-body');
        $requestHash2 = new RequestHash($hash2);

        $this->assertFalse($requestHash1->equals($requestHash2));
        $this->assertFalse($requestHash2->equals($requestHash1));
    }
}
