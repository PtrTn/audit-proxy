<?php

namespace App\Factory;

use App\ValueObject\RequestHash;

class RequestHashFactory
{
    public function createFromRequest(string $requestBody): RequestHash
    {
        $hash = md5($requestBody);
        return new RequestHash($hash);
    }
}
