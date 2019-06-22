<?php

declare(strict_types=1);

namespace App\Application\Query;

use App\Application\Factory\RequestHashFactory;
use App\Infrastructure\Entity\CachedResponse;
use App\Infrastructure\Repository\CachedResponseRepository;

class FindCachedResponseQueryHandler
{
    /** @var RequestHashFactory */
    private $hashService;

    /** @var CachedResponseRepository */
    private $responseRepository;

    public function __construct(
        RequestHashFactory $hashService,
        CachedResponseRepository $responseRepository
    ) {
        $this->hashService        = $hashService;
        $this->responseRepository = $responseRepository;
    }

    public function handle(FindCachedResponseQuery $query) : ?CachedResponse
    {
        $hash     = $this->hashService->createFromRequest($query->getRequestBody());
        $response = $this->responseRepository->findByRequestHash($hash);

        if ($response === null) {
            return null;
        }

        if (! $response->isValid()) {
            return null;
        }

        return $response;
    }
}
