<?php

declare(strict_types=1);

namespace App\Application\Factory;

use App\Domain\ValueObject\RequestHash;
use function md5;

class RequestHashFactory
{
    public function createFromRequest(string $requestBody) : RequestHash
    {
        $hash = md5($requestBody);

        return new RequestHash($hash);
    }
}
