<?php

declare(strict_types=1);

namespace App\ChainCommandBundle\Helper;

use Psr\Log\LoggerInterface;

class CommandExecutorLoggerHelper
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function logBeforeExecute(string $mainCommandName, array $commands): void
    {
        $this->logger->info(sprintf(
            '"%s" is a master command of a command chain that has registered member commands',
            $mainCommandName,
        ));

        foreach ($commands as $commandName) {
            $this->logger->info(sprintf(
                '"%s" registered as a member of "%s" command chain',
                $commandName,
                $mainCommandName,
            ));
        }

        $this->logger->info(sprintf(
            'Executing "%s" command itself first:',
            $mainCommandName
        ));
    }

    public function logAfterMainCommandExecuted(string $mainCommandName): void
    {
        $this->logger->info(sprintf(
            'Executing "%s" chain members:',
            $mainCommandName,
        ));
    }

    public function logCompleteExecution(string $mainCommandName): void
    {
        $this->logger->info(sprintf(
            'Execution of "%s" chain completed.',
            $mainCommandName,
        ));
    }

    public function executionError(string $executionCommandName, string $mainChainCommandName): string
    {
        $message = sprintf(
            'Error: "%s" command is a member of "%s" command chain and cannot be executed on its own.',
            $executionCommandName,
            $mainChainCommandName,
        );

        $this->logger->info($message);

        return $message;
    }
}
