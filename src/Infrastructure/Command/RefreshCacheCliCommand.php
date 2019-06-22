<?php

namespace App\Infrastructure\Command;

use App\Application\Command\RefreshCacheCommand;
use App\Application\Command\RefreshCacheCommandHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RefreshCacheCliCommand extends Command
{
    public const NAME = 'app:cache:refresh';

    /**
     * @var RefreshCacheCommandHandler
     */
    private $commandHandler;

    public function __construct(RefreshCacheCommandHandler $commandHandler)
    {
        parent::__construct();
        $this->commandHandler = $commandHandler;
    }

    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Refresh cached audits')
            ->setHelp('This command will retrieve fresh audit data for each cached item');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Starting cache refresh');
        $this->commandHandler->handle(new RefreshCacheCommand());
        $output->writeln('Done refreshing cache');
    }
}
