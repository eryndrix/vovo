<?php declare(strict_types=1);

namespace App\Buses;

use Illuminate\Contracts\Bus\Dispatcher;

/**
 * @implements CommandBusInterface
 */
final class CommandBus implements CommandBusInterface
{
    /**
     * Bus dispatcher instance.
     * 
     * @phpstan-var Dispatcher $commandBus
     */
    public function __construct(
        private Dispatcher $commandBus
    ) {}

    /**
     * Sends a command through the dispatcher.
     * 
     * @phpstan-param object $command
     * @phpstan-return mixed
     */
    public function send(object $command): mixed
    {
        return $this->commandBus->dispatch(
            command: $command
        );
    }
    
    /**
     * Registers command-to-handler mappings with the dispatcher.
     * 
     * @phpstan-param array<class-string<object>, class-string> $map
     * @phpstan-return void
     */
    public function register(array $map): void
    {
        $this->commandBus->map(map: $map);
    }
}
