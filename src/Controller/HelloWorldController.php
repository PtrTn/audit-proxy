<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class HelloWorldController
{
    public function index(): Response
    {
        return new JsonResponse('Hello world');
    }
}
