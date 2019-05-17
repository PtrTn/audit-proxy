<?php

namespace App\Command;

use App\Dto\UncachedResponse;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Request;

class StoreResponseCommand
{

    /**
     * @var Request
     */
    private $request;

    /**
     * @var UncachedResponse
     */
    private $response;

    public function __construct(Request $request, UncachedResponse $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return UncachedResponse
     */
    public function getResponse(): UncachedResponse
    {
        return $this->response;
    }

}
