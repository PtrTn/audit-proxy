<?php

namespace App\Application\Command;

class StoreCacheRequestCommand
{
    /**
     * @var string
     */
    private $requestBody;

    public function __construct(string $requestBody)
    {
        $this->requestBody = $requestBody;
    }

    public function getRequestBody(): string
    {
        return $this->requestBody;
    }

}
