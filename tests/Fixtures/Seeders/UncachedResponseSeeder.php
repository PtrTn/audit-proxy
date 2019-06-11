<?php

namespace App\Tests\Fixtures\Seeders;

use App\Infrastructure\Entity\UncachedResponse;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UncachedResponseSeeder extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $cachedResponse = new UncachedResponse();
        $cachedResponse->setId(1);
        $cachedResponse->setRequestHash('some-hash');
        $cachedResponse->setRequestBody('some-request');
        $cachedResponse->setCreatedAt(new \DateTimeImmutable('now'));
        $manager->persist($cachedResponse);

        $manager->flush();
    }
}
