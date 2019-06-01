<?php

namespace App\Application\Command;

use App\Infrastructure\Factory\CachedResponseFactory;
use App\Application\Factory\RequestHashFactory;
use App\Infrastructure\Repository\CachedResponseRepository;

class StoreCacheResponseCommandHandler
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

    public function handle(StoreCacheResponseCommand $command): void
    {
        $hash = $this->requestHashFactory->createFromRequest($command->getRequestBody());
        $cachedResponse = $this->cachedResponseFactory->createFromResponse(
            $hash,
            $command->getRequestBody(),
            $command->getResponse()
        );
        $this->responseRepository->save($cachedResponse);
    }
}
