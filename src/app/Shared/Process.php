<?php declare(strict_types=1);

namespace App\Shared;

use Illuminate\Pipeline\Pipeline;

/**
 * @phpstan-template TContext of Context
 * @phpstan-template TResult of Result
 */
abstract class Process
{
    /**
     * Pipeline handler class names.
     * 
     * @phpstan-var list<class-string>
     */
    protected array $handlers = [];

    /**
     * Runs the pipeline with the provided context.
     * 
     * @phpstan-param TContext $context
     * @phpstan-return TResult
     */
    public function run(Context $context): Result
    {
        return app(
            abstract: Pipeline::class
        )->send(
            passable: $context
        )->through(
            pipes: $this->handlers
        )->thenReturn();
    }
}
