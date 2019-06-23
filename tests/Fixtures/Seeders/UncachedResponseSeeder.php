<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\Seeders;

use App\Infrastructure\Entity\UncachedResponse;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UncachedResponseSeeder extends Fixture
{
    public function load(ObjectManager $manager) : void
    {
        $cachedResponse = new UncachedResponse();
        $cachedResponse->setId(1);
        $cachedResponse->setRequestHash('some-hash');
        $cachedResponse->setRequestBody('some-request');
        $cachedResponse->setCreatedAt(new DateTimeImmutable('now'));
        $manager->persist($cachedResponse);

        $manager->flush();
    }
}
