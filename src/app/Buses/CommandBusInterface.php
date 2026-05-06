<?php declare(strict_types=1);

namespace App\Buses;

 /**
  * @phpstan-template TCommand of object
  * @phpstan-template TResult
  */
interface CommandBusInterface
{
    /**
     * Sends a command and returns its result.
     * 
     * @phpstan-param TCommand $command
     * @phpstan-return TResult
     */
    public function send(object $command): mixed;

    /**
     * Maps commands to their corresponding handlers.
     * 
     * @phpstan-param array<class-string<TCommand>, class-string> $map
     * @phpstan-return void
     */
    public function register(array $map): void;
}
