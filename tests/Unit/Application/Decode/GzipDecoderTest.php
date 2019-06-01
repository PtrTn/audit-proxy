<?php

namespace Unit\Application\Decode;

use App\Application\Decode\GzipDecoder;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class GzipDecoderTest extends TestCase
{
    /**
     * @var GzipDecoder
     */
    private $decoder;

    public function setUp()
    {
        $this->decoder = new GzipDecoder();
    }

    /**
     * @test
     * @dataProvider requestData
     *
     * @param string $request
     */
    public function shouldDecodeIfGzipped(string $request)
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
    public function shouldNotDecodeIfNotGzipped()
    {
        $possibleGzipString = 'non-gzipped-string';
        $decoded = $this->decoder->decode($possibleGzipString);

        $this->assertEquals($possibleGzipString, $decoded);
    }

    /**
     * @test
     */
    public function shouldErrorOnInvalidGzipString()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Could not decode gzipped request contents');

        $invalidGzipString = "\x1f" . "\x8b" . "\x08" . 'and-some-invalid-part';

        $this->decoder->decode($invalidGzipString);
    }

    public function requestData()
    {
        return [
            'gzipped yarn request body' => [file_get_contents(__DIR__ . '/../../../fixtures/request-body-yarn.gz')],
            'gzipped npm request body' => [file_get_contents(__DIR__ . '/../../../fixtures/request-body-npm.gz')],
            'yarn request body' => [file_get_contents(__DIR__ . '/../../../fixtures/request-body-yarn.gz')],
            'npm request body'  => [file_get_contents(__DIR__ . '/../../../fixtures/request-body-npm.gz')],
        ];
    }
}
