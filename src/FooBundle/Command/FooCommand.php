<?php

declare(strict_types=1);

namespace App\FooBundle\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FooCommand extends Command
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger, string $name = null)
    {
        parent::__construct($name);

        $this->logger = $logger;
    }

    protected function configure()
    {
        $this->setName('foo:hello');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $message = 'Hello from Foo!';

        $this->logger->info($message);
        $output->writeln($message);

        return self::SUCCESS;
    }
}
