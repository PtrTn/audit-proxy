<?php

namespace App\Tests\Integration\Infrastructure\Command;

use App\Infrastructure\Command\RetryCacheCliCommand;
use App\Tests\Fixtures\Seeders\UncachedResponseSeeder;
use App\Tests\Helpers\KernelTestCase;

class RetryCacheCliCommandTest extends KernelTestCase
{
    public function setUp()
    {
        $this->runSeeder(UncachedResponseSeeder::class);
    }

    public function testExecute()
    {
        $output = $this->runCommand(RetryCacheCliCommand::NAME);

        $this->assertContains('Starting cache retry', $output);
        $this->assertContains('Done retrying cache', $output);
    }
}