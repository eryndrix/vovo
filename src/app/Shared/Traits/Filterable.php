<?php declare(strict_types=1);

namespace App\Shared\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;

trait Filterable
{
    /**
     * Runs the given query builder through the provided filters using a pipeline.
     *
     * @phpstan-param Builder $query
     * @phpstan-param array $filters
     * 
     * @phpstan-return Builder
     */
    public function scopeFilter(
        Builder $query, array $filters = []): Builder
    {
        return app(
            abstract: Pipeline::class
        )->send(
            passable: $query
        )->through(
            pipes: $filters
        )->thenReturn();
    }
}
