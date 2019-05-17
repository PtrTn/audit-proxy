<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class RegistryTestController
{
    public function index(): Response
    {
        return new JsonResponse('Simulated 200 response');
    }

    public function error(): Response
    {
        return new JsonResponse('Simulated error response', 503);
    }
}
