<?php

declare(strict_types=1);

namespace App\Application\Decode;

use RuntimeException;
use function gzdecode;
use function implode;
use function mb_strpos;

class GzipDecoder
{
    public function decode(string $possibleGzipString) : string
    {
        if (! $this->isGzipped($possibleGzipString)) {
            return $possibleGzipString;
        }

        $decompressed = @gzdecode($possibleGzipString);
        if ($decompressed !== false) {
            return $decompressed;
        }

        throw new RuntimeException('Could not decode gzipped request contents');
    }

    private function isGzipped(string $possibleGzipString) : bool
    {
        $gzipStartingBytes = implode('', ["\x1f", "\x8b", "\x08"]);

        return mb_strpos($possibleGzipString, $gzipStartingBytes) === 0;
    }
}
