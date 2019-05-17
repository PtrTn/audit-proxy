<?php

namespace App\Controller;

use App\Factory\CachedResponseFactory;
use App\Factory\RequestHashFactory;
use App\Mapper\RequestMapper;
use App\Repository\CachedResponseRepository;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProxyController
{
    /**
     * @var RequestHashFactory
     */
    private $hashService;

    /**
     * @var CachedResponseRepository
     */
    private $responseRepository;

    /**
     * @var RequestMapper
     */
    private $requestMapper;

    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * @var \App\Factory\CachedResponseFactory
     */
    private $cachedResponseFactory;

    public function __construct(
        RequestHashFactory $hashService,
        CachedResponseRepository $responseRepository,
        RequestMapper $requestMapper,
        GuzzleClient $client,
        CachedResponseFactory $cachedResponseFactory
    ){
        $this->hashService = $hashService;
        $this->responseRepository = $responseRepository;
        $this->requestMapper = $requestMapper;
        $this->client = $client;
        $this->cachedResponseFactory = $cachedResponseFactory;
    }

    public function index(Request $request): Response
    {
        // Retrieve cached response.
        $hash = $this->hashService->createFromRequest($request);
        $response = $this->responseRepository->findByRequestHash($hash);
        if ($response !== null && $response->isValid()) {
            return $response->toSymfonyResponse(true);
        }

        // Retrieve uncached response.
        $guzzleRequest = $this->requestMapper->httpToGuzzle($request);
        try {
            $response = $this->client->send($guzzleRequest, ['timeout' => 5]);
        } catch (ConnectException $e) {
            return new JsonResponse(['error' => 'Registry time-out'], 503);
        } catch (GuzzleException $e) {
            return new JsonResponse(['error' => 'Registry error'], 503);
        }

        // Store cached response.
        $cachedResponse = $this->cachedResponseFactory->createFromResponse($hash, $response);
        $this->responseRepository->save($cachedResponse);

        return $cachedResponse->toSymfonyResponse(false);
    }
}
