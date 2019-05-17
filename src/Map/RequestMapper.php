<?php

namespace App\Mapper;

use GuzzleHttp\Psr7\Request as GuzzleRequest;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class RequestMapper
{
    private const REGISTRY_URL = 'https://registry.yarnpkg.com';

    public function httpToGuzzle(HttpRequest $request): GuzzleRequest
    {
        return new GuzzleRequest(
            $request->getMethod(),
            self::REGISTRY_URL . $request->getRequestUri(),
            [
                'Content-Type'  => 'application/json',
                'Accept'  => 'application/json',
            ],
            $request->getContent(true)
        );
    }

}
