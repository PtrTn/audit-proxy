<?php

namespace App\Infrastructure\Command;

use App\Application\Command\RetryCacheCommand;
use App\Application\Command\RetryCacheCommandHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RetryCacheCliCommand extends Command
{
    public const NAME = 'app:cache:retry';

    /**
     * @var RetryCacheCommandHandler
     */
    private $commandHandler;

    public function __construct(RetryCacheCommandHandler $commandHandler)
    {
        parent::__construct();
        $this->commandHandler = $commandHandler;
    }

    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Retry to cache previously failed audits')
            ->setHelp('This command will attempt to create cache for audit requests which previously failed');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Starting cache retry');
        $this->commandHandler->handle(new RetryCacheCommand());
        $output->writeln('Done retrying cache');
    }
}
