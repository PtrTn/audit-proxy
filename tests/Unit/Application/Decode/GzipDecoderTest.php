<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Decode;

use App\Application\Decode\GzipDecoder;
use App\Tests\Helpers\FixtureAware;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use function implode;
use function json_decode;

class GzipDecoderTest extends TestCase
{
    use FixtureAware;

    /** @var GzipDecoder */
    private $decoder;

    public function setUp() : void
    {
        $this->decoder = new GzipDecoder();
    }

    /**
     * @test
     * @dataProvider requestData
     */
    public function shouldDecodeIfGzipped(string $request) : void
    {
        $decoded = $this->decoder->decode($request);
        $json    = json_decode($decoded, true);

        $this->assertArrayHasKey('requires', $json);
        $this->assertArrayHasKey('dependencies', $json);
        $this->assertArrayHasKey('install', $json);
        $this->assertArrayHasKey('remove', $json);
        $this->assertArrayHasKey('metadata', $json);
    }

    /**
     * @test
     */
    public function shouldNotDecodeIfNotGzipped() : void
    {
        $possibleGzipString = 'non-gzipped-string';
        $decoded            = $this->decoder->decode($possibleGzipString);

        $this->assertEquals($possibleGzipString, $decoded);
    }

    /**
     * @test
     */
    public function shouldErrorOnInvalidGzipString() : void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Could not decode gzipped request contents');

        $gzipStartingBytes = implode('', ["\x1f", "\x8b", "\x08"]);
        $invalidGzipString = $gzipStartingBytes . 'and-some-invalid-part';

        $this->decoder->decode($invalidGzipString);
    }

    /**
     * @return string[][]
     */
    public function requestData() : array
    {
        return [
            'gzipped yarn request body' => [$this->getContentsFromFile('request-body-yarn.gz')],
            'gzipped npm request body'  => [$this->getContentsFromFile('request-body-npm.gz')],
            'yarn request body'         => [$this->getContentsFromFile('request-body-yarn.gz')],
            'npm request body'          => [$this->getContentsFromFile('request-body-npm.gz')],
        ];
    }
}
