<?php

namespace App\Application\Command;

use App\Factory\CachedResponseFactory;
use App\Factory\RequestHashFactory;
use App\Infrastructure\Repository\CachedResponseRepository;

class StoreResponseCommandHandler
{

    /**
     * @var RequestHashFactory
     */
    private $requestHashFactory;

    /**
     * @var CachedResponseRepository
     */
    private $responseRepository;

    /**
     * @var CachedResponseFactory
     */
    private $cachedResponseFactory;

    public function __construct(
        RequestHashFactory $requestHashFactory,
        CachedResponseRepository $responseRepository,
        CachedResponseFactory $cachedResponseFactory
    ) {
        $this->requestHashFactory = $requestHashFactory;
        $this->responseRepository = $responseRepository;
        $this->cachedResponseFactory = $cachedResponseFactory;
    }

    public function handle(StoreResponseCommand $command): void
    {
        $hash = $this->requestHashFactory->createFromRequest($command->getRequestBody());
        $cachedResponse = $this->cachedResponseFactory->createFromResponse($hash, $command->getResponse());
        $this->responseRepository->save($cachedResponse);
    }
}
