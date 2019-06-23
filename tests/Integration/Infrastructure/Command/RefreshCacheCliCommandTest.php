<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Command;

use App\Infrastructure\Command\RefreshCacheCliCommand;
use App\Tests\Fixtures\Seeders\OutdatedCachedResponseSeeder;
use App\Tests\Helpers\KernelTestCase;

class RefreshCacheCliCommandTest extends KernelTestCase
{
    public function setUp() : void
    {
        $this->runSeeder(OutdatedCachedResponseSeeder::class);
    }

    public function testExecute() : void
    {
        $output = $this->runCommand(RefreshCacheCliCommand::NAME);

        $this->assertContains('Starting cache refresh', $output);
        $this->assertContains('Done refreshing cache', $output);
    }
}
