<?php

namespace App\Application\Decode;

class GzipDecoder
{
    public function decode(string $possibleGzipString)
    {
        if (!$this->isGzipped($possibleGzipString)) {
            return $possibleGzipString;
        }

        $decompressed = gzdecode($possibleGzipString);
        if ($decompressed !== false) {
            return $decompressed;
        }

        throw new \RuntimeException('Could not decode gzipped request contents');
    }

    private function isGzipped(string $possibleGzipString): bool
    {
        return mb_strpos($possibleGzipString , "\x1f" . "\x8b" . "\x08") === 0;
    }

}
