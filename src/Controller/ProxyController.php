<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

class ProxyController
{
    public function index()
    {
        return new JsonResponse('hello world');
    }
}
