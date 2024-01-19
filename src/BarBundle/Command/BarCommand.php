<?php

declare(strict_types=1);

namespace App\BarBundle\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BarCommand extends Command
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger, string $name = null)
    {
        parent::__construct($name);

        $this->logger = $logger;
    }

    protected function configure()
    {
        $this->setName('bar:hi');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $message = 'Hi from Bar!';

        $this->logger->info($message);
        $output->writeln($message);

        return self::SUCCESS;
    }
}
