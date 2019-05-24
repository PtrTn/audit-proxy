<?php

namespace App\Query;

use App\Dto\UncachedResponse;
use App\Factory\UncachedResponseFactory;
use App\Mapper\RequestMapper;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;

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

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        RequestMapper $requestMapper,
        GuzzleClient $client,
        UncachedResponseFactory $uncachedResponseFactory,
        LoggerInterface $logger
    ) {
        $this->requestMapper = $requestMapper;
        $this->client = $client;
        $this->uncachedResponseFactory = $uncachedResponseFactory;
        $this->logger = $logger;
    }

    public function handle(FindUncachedResponseQuery $query): ?UncachedResponse
    {
        $guzzleRequest = $this->requestMapper->httpToGuzzle($query->getRequestBody());
        try {
            $response = $this->client->send($guzzleRequest, ['timeout' => 10]);
            return $this->uncachedResponseFactory->createFromResponse($response);
        } catch (GuzzleException $e) {
            if ($e->getCode() !== 503) {
                $this->logger->warning('Error retrieving uncached response: "{error}"', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTrace()
                ]);
            }
            return null;
        }
    }
}
