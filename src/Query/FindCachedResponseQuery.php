<?php

namespace App\Query;

use Symfony\Component\HttpFoundation\Request;

class FindCachedResponseQuery
{
    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
}
