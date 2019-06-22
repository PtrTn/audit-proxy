<?php

namespace Integration\Infrastructure\Command;

use App\Infrastructure\Command\CacheCleanupCliCommand;
use App\Tests\Fixtures\Seeders\UnusedCachedResponseSeeder;
use App\Tests\Helpers\KernelTestCase;

class CacheCleanupCliCommandTest extends KernelTestCase
{
    public function setUp()
    {
        $this->runSeeder(UnusedCachedResponseSeeder::class);
    }

    public function testExecute()
    {
        $output = $this->runCommand(CacheCleanupCliCommand::NAME);

        $this->assertContains('Starting cache cleanup', $output);
        $this->assertContains('Done cleaning up cache', $output);
    }
}