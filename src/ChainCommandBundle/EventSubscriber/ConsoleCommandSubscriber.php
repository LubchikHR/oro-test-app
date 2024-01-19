<?php

declare(strict_types=1);

namespace App\ChainCommandBundle\EventSubscriber;

use App\ChainCommandBundle\Helper\CommandExecutorLoggerHelper;
use App\ChainCommandBundle\Service\CommandExecutor;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ConsoleCommandSubscriber
 */
class ConsoleCommandSubscriber implements EventSubscriberInterface
{
    private CommandExecutor $commandExecutor;
    private CommandExecutorLoggerHelper $logHelper;

    public function __construct(CommandExecutor $commandExecutor, CommandExecutorLoggerHelper $logHelper)
    {
        $this->commandExecutor = $commandExecutor;
        $this->logHelper = $logHelper;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ConsoleEvents::COMMAND => 'onConsoleCommand',
            ConsoleEvents::TERMINATE => 'onConsoleCommandExecuted',
        ];
    }

    public function onConsoleCommand(ConsoleCommandEvent $event)
    {
        $commandName = $event->getCommand()->getName();
        if ($this->commandExecutor->isChainMember($commandName)) {
            if ($this->commandExecutor->isMainCommand($commandName)) {
                $this->logHelper->logBeforeExecute(
                    $this->commandExecutor->getMainCommandName(),
                    $this->commandExecutor->getCommandsNames(),
                );
            } else {
                $message = $this->logHelper->executionError($commandName, $this->commandExecutor->getMainCommandName());
                $event->getOutput()->writeln($message);
                $event->disableCommand();
            }
        }
    }

    public function onConsoleCommandExecuted(ConsoleTerminateEvent $event)
    {
        $commandName = $event->getCommand()->getName();
        if ($this->commandExecutor->isMainCommand($commandName)) {
            $this->logHelper->logAfterMainCommandExecuted($commandName);
            $this->commandExecutor->executeStackCommands();
            $this->logHelper->logCompleteExecution($commandName);
        }
    }
}
