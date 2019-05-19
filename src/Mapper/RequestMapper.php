<?php

namespace App\Mapper;

use GuzzleHttp\Psr7\Request as GuzzleRequest;

class RequestMapper
{
    private const REGISTRY_URL = 'https://registry.yarnpkg.com/-/npm/v1/security/audits';

    public function httpToGuzzle(string $requestBody): GuzzleRequest
    {
        return new GuzzleRequest(
            'POST',
            self::REGISTRY_URL,
            [
                'Content-Type'  => 'application/json',
                'Accept'  => 'application/json',
            ],
            $requestBody
        );
    }

}
