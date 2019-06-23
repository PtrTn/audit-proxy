<?php

declare(strict_types=1);

namespace App\Application\Dto;

use Symfony\Component\HttpFoundation\Response;

class UncachedResponse
{
    /** @var string */
    private $response;

    public function __construct(string $response)
    {
        $this->response = $response;
    }

    public function getResponse() : string
    {
        return $this->response;
    }

    public function toSymfonyResponse() : Response
    {
        return new Response(
            $this->response,
            200,
            [
                'Content-Type' => 'application/json; charset=utf-8',
                'X-Cache' => 'MISS',
            ]
        );
    }
}
