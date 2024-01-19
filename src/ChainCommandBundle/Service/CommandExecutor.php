<?php

declare(strict_types=1);

namespace App\ChainCommandBundle\Service;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * This class execute the chained commands.
 *
 * Class CommandExecutor
 * @package App\ChainCommandBundle\Service
 */
class CommandExecutor
{
    public const MAIN_COMMAND = 'main';
    public const SECONDARY_COMMAND = 'secondary';

    private Command $mainCommand;
    private array $commands = [];

    public function addCommand(string $alias, Command $command): void
    {
        if ($alias === self::MAIN_COMMAND) {
            $this->mainCommand = $command;
        } else {
            $this->commands[$command->getName()] = $command;
        }
    }

    public function isMainCommand(string $commandName): bool
    {
        if (isset($this->mainCommand)) {
            return $this->mainCommand->getName() === $commandName;
        }

        return false;
    }

    public function isChainMember(string $commandName): bool
    {
        return $this->isMainCommand($commandName) || array_key_exists($commandName, $this->commands);
    }

    public function getMainCommandName(): ?string
    {
        return $this->mainCommand->getName() ?? null;
    }

    public function getCommandsNames(): array
    {
        return array_keys($this->commands);
    }

    public function executeStackCommands(): void
    {
        /** @var Command $command */
        foreach ($this->commands as $command) {
            $input = new ArrayInput([]);
            $output = new BufferedOutput();

            $command->run($input, $output);

            echo $output->fetch();
        }
    }
}
