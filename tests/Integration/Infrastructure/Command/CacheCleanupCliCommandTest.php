<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Command;

use App\Infrastructure\Command\CacheCleanupCliCommand;
use App\Tests\Fixtures\Seeders\UnusedCachedResponseSeeder;
use App\Tests\Helpers\KernelTestCase;

class CacheCleanupCliCommandTest extends KernelTestCase
{
    public function setUp() : void
    {
        $this->runSeeder(UnusedCachedResponseSeeder::class);
    }

    public function testExecute() : void
    {
        $output = $this->runCommand(CacheCleanupCliCommand::NAME);

        $this->assertContains('Starting cache cleanup', $output);
        $this->assertContains('Done cleaning up cache', $output);
    }
}
