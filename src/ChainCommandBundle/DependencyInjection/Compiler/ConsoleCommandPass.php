<?php

declare(strict_types=1);

namespace App\ChainCommandBundle\DependencyInjection\Compiler;

use App\ChainCommandBundle\Service\CommandExecutor;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use InvalidArgumentException;

/**
 * Class ConsoleCommandPass
 */
class ConsoleCommandPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(CommandExecutor::class)) {
            return;
        }

        $commandExecutorDefinition = $container->findDefinition(CommandExecutor::class);
        $taggedServices = $container->findTaggedServiceIds('console.command.chain');

        foreach ($taggedServices as $id => $tags) {
            ['alias' => $alias] = array_shift($tags);

            if (!$alias) {
                throw new InvalidArgumentException(
                    sprintf('Chaining command "%s" should be with alias.', $id)
                );
            }

            $commandExecutorDefinition->addMethodCall(
                'addCommand',
                [$alias, new Reference($id)]
            );
        }
    }
}
