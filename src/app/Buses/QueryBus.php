<?php declare(strict_types=1);

namespace App\Buses;

use Illuminate\Contracts\Bus\Dispatcher;

/**
 * @implements QueryBusInterface
 */
final class QueryBus implements QueryBusInterface
{
    /**
     * Bus dispatcher instance.
     * 
     * @phpstan-var Dispatcher $queryBus
     */
    public function __construct(
        private Dispatcher $queryBus
    ) {}

    /**
     * Executes the specified query using the dispatcher.
     * 
     * @phpstan-param object $query
     * @phpstan-return mixed
     */
    public function ask(object $query): mixed
    {
        return $this->queryBus->dispatch(command: $query);
    }
    
    /**
     * Maps query types to their handlers.
     * 
     * @phpstan-param array<class-string<object>, class-string> $map
     * @phpstan-return void
     */
    public function register(array $map): void
    {
        $this->queryBus->map(map: $map);
    }
}
