<?php

namespace App\Application\Command;

use App\Application\Factory\RequestHashFactory;
use App\Infrastructure\Factory\UncachedResponseFactory;
use App\Infrastructure\Repository\UncachedResponseRepository;

class StoreCacheRequestCommandHandler
{
    /**
     * @var RequestHashFactory
     */
    private $requestHashFactory;

    /**
     * @var UncachedResponseRepository
     */
    private $responseRepository;

    /**
     * @var UncachedResponseFactory
     */
    private $responseFactory;

    public function __construct(
        RequestHashFactory $requestHashFactory,
        UncachedResponseRepository $responseRepository,
        UncachedResponseFactory $responseFactory
    ) {
        $this->requestHashFactory = $requestHashFactory;
        $this->responseRepository = $responseRepository;
        $this->responseFactory = $responseFactory;
    }

    public function handle(StoreCacheRequestCommand $command): void
    {
        $hash = $this->requestHashFactory->createFromRequest($command->getRequestBody());
        if ($this->responseRepository->hasRequestWithHash($hash)) {
            return;
        }

        $cachedResponse = $this->responseFactory->createFromResponse(
            $hash,
            $command->getRequestBody()
        );
        $this->responseRepository->save($cachedResponse);
    }
}
