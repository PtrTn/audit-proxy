<?php

namespace App\Infrastructure\Command;

use App\Application\Command\CleanupCacheCommand;
use App\Application\Command\CleanupCacheCommandHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CacheCleanupCliCommand extends Command
{
    public const NAME = 'app:cache:cleanup';

    /**
     * @var CleanupCacheCommandHandler
     */
    private $commandHandler;

    public function __construct(CleanupCacheCommandHandler $commandHandler)
    {
        parent::__construct();
        $this->commandHandler = $commandHandler;
    }

    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Cleanup inactive cached audits')
            ->setHelp('This command will clean up inactive audit data for each cached item');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Starting cache cleanup');
        $this->commandHandler->handle(new CleanupCacheCommand());
        $output->writeln('Done cleaning up cache');
    }
}
