<?php

declare(strict_types=1);

namespace App\Tests\Helpers;

use GuzzleHttp\Psr7\Stream;
use Psr\Http\Message\StreamInterface;
use RuntimeException;
use const JSON_ERROR_NONE;
use function file_get_contents;
use function fopen;
use function json_decode;
use function json_last_error;
use function sprintf;

trait FixtureAware
{
    /** @var string */
    private $fixturePath = __DIR__ . '/../Fixtures/Files/';

    public function getContentsFromFile(string $filename) : string
    {
        $fixture  = $this->getFixturePath($filename);
        $contents = file_get_contents($fixture);
        if ($contents === false) {
            throw new RuntimeException(sprintf('Unable to get contents for fixture "%s"', $filename));
        }

        return $contents;
    }

    /**
     * @return string[]
     */
    public function getContentsFromJson(string $filename) : array
    {
        $contents = $this->getContentsFromFile($filename);
        $decoded  = json_decode($contents, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException(sprintf('Unable to decode for fixture "%s"', $filename));
        }

        return $decoded;
    }

    public function getFixtureResource(string $filename) : StreamInterface
    {
        $fixture  = $this->getFixturePath($filename);
        $resource = fopen($fixture, 'r');
        if ($resource === false) {
            throw new RuntimeException(sprintf('Unable to get resource for fixture "%s"', $fixture));
        }

        return new Stream($resource);
    }

    private function getFixturePath(string $filename) : string
    {
        return sprintf(
            '%s/%s',
            $this->fixturePath,
            $filename
        );
    }
}
