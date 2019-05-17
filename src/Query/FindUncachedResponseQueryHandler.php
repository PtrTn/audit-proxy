<?php

namespace App\Query;

use App\Dto\UncachedResponse;
use App\Factory\UncachedResponseFactory;
use App\Mapper\RequestMapper;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

class FindUncachedResponseQueryHandler
{

    /**
     * @var RequestMapper
     */
    private $requestMapper;

    /**
     * @var GuzzleClient
     */
    private $client;

    /**
     * @var UncachedResponseFactory
     */
    private $uncachedResponseFactory;

    public function __construct(
        RequestMapper $requestMapper,
        GuzzleClient $client,
        UncachedResponseFactory $uncachedResponseFactory
    ) {
        $this->requestMapper = $requestMapper;
        $this->client = $client;
        $this->uncachedResponseFactory = $uncachedResponseFactory;
    }

    public function handle(FindUncachedResponseQuery $query): ?UncachedResponse
    {
        $guzzleRequest = $this->requestMapper->httpToGuzzle($query->getRequest());
        try {
            $response = $this->client->send($guzzleRequest, ['timeout' => 5]);
            return $this->uncachedResponseFactory->createFromResponse($response);
        } catch (GuzzleException $e) {
            return null;
        }
    }
}
