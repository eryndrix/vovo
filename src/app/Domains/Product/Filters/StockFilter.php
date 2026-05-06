<?php declare(strict_types=1);

namespace App\Domains\Product\Filters;

use Illuminate\Database\Eloquent\Builder;
use App\Shared\Filter;

/**
 * @extends Filter
 */
final class StockFilter extends Filter
{
    /**
     * Stock availability filter constructor.
     * 
     * @phpstan-param bool $inStock
     */
    public function __construct(
        private readonly bool $inStock
    ) {}

    /**
     * Apply stock filter to the Eloquent query builder.
     * 
     * @phpstan-param Builder $builder
     * @phpstan-param \Closure $next
     * 
     * @phpstan-return Builder
     */
    public function __invoke(
        Builder $builder, \Closure $next): Builder
    {
        $builder->where(
            column: 'in_stock',
            operator: '=',
            value: $this->inStock
        );

        return $next($builder);
    }
}
