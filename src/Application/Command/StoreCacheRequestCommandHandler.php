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
    private $unresponseRepository;

    /**
     * @var UncachedResponseFactory
     */
    private $uncachedResponseFactory;

    public function __construct(
        RequestHashFactory $requestHashFactory,
        UncachedResponseRepository $responseRepository,
        UncachedResponseFactory $cachedResponseFactory
    ) {
        $this->requestHashFactory = $requestHashFactory;
        $this->unresponseRepository = $responseRepository;
        $this->uncachedResponseFactory = $cachedResponseFactory;
    }

    public function handle(StoreCacheRequestCommand $command): void
    {
        $hash = $this->requestHashFactory->createFromRequest($command->getRequestBody());
        $cachedResponse = $this->uncachedResponseFactory->createFromResponse(
            $hash,
            $command->getRequestBody()
        );
        $this->unresponseRepository->save($cachedResponse);
    }
}
