<?php

namespace App\Tests\Unit\Application\Decode;

use App\Application\Decode\GzipDecoder;
use App\Tests\Helpers\FixtureAwareTrait;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class GzipDecoderTest extends TestCase
{
    use FixtureAwareTrait;

    /**
     * @var GzipDecoder
     */
    private $decoder;

    public function setUp(): void
    {
        $this->decoder = new GzipDecoder();
    }

    /**
     * @test
     * @dataProvider requestData
     *
     * @param string $request
     */
    public function shouldDecodeIfGzipped(string $request): void
    {
        $decoded = $this->decoder->decode($request);
        $json = json_decode($decoded, true);

        $this->assertArrayHasKey('requires', $json);
        $this->assertArrayHasKey('dependencies', $json);
        $this->assertArrayHasKey('install', $json);
        $this->assertArrayHasKey('remove', $json);
        $this->assertArrayHasKey('metadata', $json);
    }

    /**
     * @test
     */
    public function shouldNotDecodeIfNotGzipped(): void
    {
        $possibleGzipString = 'non-gzipped-string';
        $decoded = $this->decoder->decode($possibleGzipString);

        $this->assertEquals($possibleGzipString, $decoded);
    }

    /**
     * @test
     */
    public function shouldErrorOnInvalidGzipString(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Could not decode gzipped request contents');

        $invalidGzipString = "\x1f" . "\x8b" . "\x08" . 'and-some-invalid-part';

        $this->decoder->decode($invalidGzipString);
    }

    public function requestData(): array
    {
        return [
            'gzipped yarn request body' => [$this->getContentsFromFile('request-body-yarn.gz')],
            'gzipped npm request body'  => [$this->getContentsFromFile('request-body-npm.gz')],
            'yarn request body'         => [$this->getContentsFromFile('request-body-yarn.gz')],
            'npm request body'          => [$this->getContentsFromFile('request-body-npm.gz')],
        ];
    }
}
