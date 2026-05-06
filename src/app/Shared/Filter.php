<?php declare(strict_types=1);

namespace App\Shared;

use Illuminate\Database\Eloquent\Builder;

abstract class Filter
{
    /**
     * Applies the filter to the provided query builder and forwards control to the next filter in the chain.
     *
     * @phpstan-param Builder $builder
     * @phpstan-param \Closure $next
     * 
     * @phpstan-return Builder
     */
    abstract public function __invoke(Builder $builder, \Closure $next): Builder;
}
