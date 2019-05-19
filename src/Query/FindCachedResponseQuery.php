<?php

namespace App\Query;

class FindCachedResponseQuery
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
