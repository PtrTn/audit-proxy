<?php

namespace App\Application\Factory;

use GuzzleHttp\Psr7\Request as GuzzleRequest;

class GuzzleRequestFactory
{
    /**
     * @var string
     */
    private $registryUrl;

    public function __construct(string $registryUrl)
    {
        $this->registryUrl = $registryUrl;
    }

    public function fromRequestBody(string $requestBody): GuzzleRequest
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
