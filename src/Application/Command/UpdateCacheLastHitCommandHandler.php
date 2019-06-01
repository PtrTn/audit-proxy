<?php

namespace App\Application\Command;

use App\Infrastructure\Repository\CachedResponseRepository;
use Psr\Log\LoggerInterface;

class UpdateCacheLastHitCommandHandler
{
    /**
     * @var CachedResponseRepository
     */
    private $responseRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        CachedResponseRepository $responseRepository,
        LoggerInterface $logger
    ) {
        $this->responseRepository = $responseRepository;
        $this->logger = $logger;
    }

    public function handle(UpdateCacheLastHitCommand $command): void
    {
        $cachedResponse = $this->responseRepository->findOneBy(['id' => $command->getId()]);
        if ($cachedResponse === null) {
            $this->logger->warning(
                'Unable to update last cache hit because could not find record with {id}',
                [
                    'id'             => $command->getId(),
                    'lastCacheHitAt' => $command->getDateTime()->format('Y-m-d H:i'),
                ]);

            return;
        }

        $cachedResponse->setLastCacheHitAt($command->getDateTime());
        $this->responseRepository->save($cachedResponse);
    }
}
