<?php

namespace App\Factory;

use App\Domain\ValueObject\RequestHash;

class RequestHashFactory
{
    public function createFromRequest(string $requestBody): RequestHash
    {
        $hash = md5($requestBody);
        return new RequestHash($hash);
    }
}
