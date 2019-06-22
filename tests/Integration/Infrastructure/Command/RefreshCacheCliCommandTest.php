<?php

namespace App\Tests\Integration\Infrastructure\Command;

use App\Infrastructure\Command\RefreshCacheCliCommand;
use App\Tests\Fixtures\Seeders\OutdatedCachedResponseSeeder;
use App\Tests\Helpers\KernelTestCase;

class RefreshCacheCliCommandTest extends KernelTestCase
{
    public function setUp()
    {
        $this->runSeeder(OutdatedCachedResponseSeeder::class);
    }

    public function testExecute()
    {
        $output = $this->runCommand(RefreshCacheCliCommand::NAME);

        $this->assertContains('Starting cache refresh', $output);
        $this->assertContains('Done refreshing cache', $output);
    }
}