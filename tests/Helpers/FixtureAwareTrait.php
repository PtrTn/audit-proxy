<?php

namespace App\Tests\Helpers;

use GuzzleHttp\Psr7\Stream;
use Psr\Http\Message\StreamInterface;

trait FixtureAwareTrait
{
    private $fixturePath = __DIR__ . '/../Fixtures/Files/';

    public function getContentsFromFile(string $filename): string
    {
        $fixture = $this->getFixturePath($filename);
        $contents = file_get_contents($fixture);
        if ($contents === false) {
            throw new \RuntimeException(sprintf('Unable to get contents for fixture "%s"', $filename));
        }

        return $contents;
    }

    public function getContentsFromJson(string $filename): array
    {
        $contents = $this->getContentsFromFile($filename);
        $decoded = json_decode($contents, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException(sprintf('Unable to decode for fixture "%s"', $filename));
        }

        return $decoded;
    }

    public function getFixtureResource($filename): StreamInterface
    {
        $fixture = $this->getFixturePath($filename);
        $resource = fopen($fixture, 'r');
        if ($resource === false) {
            throw new \RuntimeException(sprintf('Unable to get resource for fixture "%s"', $fixture));
        }

        return new Stream($resource);
    }

    private function getFixturePath(string $filename): string
    {
        return sprintf(
            '%s/%s',
            $this->fixturePath,
            $filename
        );
    }
}
