<?php

namespace App\Tests\Fixtures\Seeders;

use App\Infrastructure\Entity\CachedResponse;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class OutdatedCachedResponseSeeder extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $cachedResponse = new CachedResponse();
        $cachedResponse->setId(1);
        $cachedResponse->setRequestHash('some-hash');
        $cachedResponse->setRequestBody('some-request');
        $cachedResponse->setResponse('some-response');
        $cachedResponse->setCreatedAt(new \DateTimeImmutable('-2 weeks'));
        $cachedResponse->setUpdatedAt(new \DateTimeImmutable('-2 hours'));
        $cachedResponse->setLastCacheHitAt(new \DateTimeImmutable('-2 weeks'));
        $manager->persist($cachedResponse);

        $manager->flush();
    }
}
