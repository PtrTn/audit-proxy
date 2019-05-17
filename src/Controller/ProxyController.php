<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\RedirectController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProxyController extends RedirectController
{
    public function index(Request $request): Response
    {
        return $this->urlRedirectAction(
            $request,
            'http://127.0.0.1:8000/test/-/npm/v1/security/audits',
            false,
            null,
            null,
            null,
            true
        );
    }
    public function test(): Response
    {
        return new JsonResponse('hello test');
    }
}
