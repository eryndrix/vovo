<?php declare(strict_types=1);

namespace App\Buses;

/**
 * @phpstan-template TQuery of object
 * @phpstan-template TResult
 */
interface QueryBusInterface
{
    /**
     * Executes the specified query and returns its result.
     * 
     * @phpstan-param TQuery $query
     * @phpstan-return TResult
     */
    public function ask(object $query): mixed;

    /**
     * Registers mappings from queries to their respective handlers.
     * 
     * @phpstan-param array<class-string<TQuery>, class-string> $map
     * @phpstan-return void
     */
    public function register(array $map): void;
}
