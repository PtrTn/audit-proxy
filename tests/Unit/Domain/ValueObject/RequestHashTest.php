<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\ValueObject;

use App\Domain\ValueObject\RequestHash;
use PHPUnit\Framework\TestCase;
use function md5;

class RequestHashTest extends TestCase
{
    /**
     * @test
     */
    public function shouldConvertToString() : void
    {
        $hash        = md5('some-request-body');
        $requestHash = new RequestHash($hash);

        $this->assertEquals($hash, (string) $requestHash);
    }

    /**
     * @test
     */
    public function shouldBeEqualForSameHash() : void
    {
        $hash1        = md5('some-request-body');
        $requestHash1 = new RequestHash($hash1);

        $hash2        = $hash1;
        $requestHash2 = new RequestHash($hash2);

        $this->assertTrue($requestHash1->equals($requestHash2));
        $this->assertTrue($requestHash2->equals($requestHash1));
    }

    /**
     * @test
     */
    public function shouldNotBeEqualIfHashesDiffer() : void
    {
        $hash1        = md5('some-request-body');
        $requestHash1 = new RequestHash($hash1);

        $hash2        = md5('some-other-request-body');
        $requestHash2 = new RequestHash($hash2);

        $this->assertFalse($requestHash1->equals($requestHash2));
        $this->assertFalse($requestHash2->equals($requestHash1));
    }
}
