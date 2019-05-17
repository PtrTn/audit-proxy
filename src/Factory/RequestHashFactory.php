<?php

namespace App\Factory;

use App\ValueObject\RequestHash;
use Symfony\Component\HttpFoundation\Request;

class RequestHashFactory
{

    public function createFromRequest(Request $request): RequestHash
    {
        $content = $request->getContent(false);
        $hash = md5($content);
        return new RequestHash($hash);
    }
}
