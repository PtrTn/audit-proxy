<?php

namespace App\Application\Command;

use App\Infrastructure\Factory\CachedResponseFactory;
use App\Application\Factory\RequestHashFactory;
use App\Infrastructure\Repository\CachedResponseRepository;
use App\Infrastructure\Repository\UncachedResponseRepository;

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

    /**
     * @var UncachedResponseRepository
     */
    private $uncachedResponseRepository;

    public function __construct(
        RequestHashFactory $requestHashFactory,
        CachedResponseRepository $responseRepository,
        CachedResponseFactory $cachedResponseFactory,
        UncachedResponseRepository $uncachedResponseRepository
    ) {
        $this->requestHashFactory = $requestHashFactory;
        $this->responseRepository = $responseRepository;
        $this->cachedResponseFactory = $cachedResponseFactory;
        $this->uncachedResponseRepository = $uncachedResponseRepository;
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

        $this->uncachedResponseRepository->deleteForHash($hash);
    }
}
