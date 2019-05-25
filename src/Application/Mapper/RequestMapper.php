<?php

namespace App\Application\Mapper;

use GuzzleHttp\Psr7\Request as GuzzleRequest;

class RequestMapper
{
    /**
     * @var string
     */
    private $registryUrl;

    public function __construct(string $registryUrl)
    {
        $this->registryUrl = $registryUrl;
    }

    public function httpToGuzzle(string $requestBody): GuzzleRequest
    {
        return new GuzzleRequest(
            'POST',
            $this->registryUrl,
            [
                'Content-Type'  => 'application/json',
                'Accept'  => 'application/json',
            ],
            $requestBody
        );
    }

}
