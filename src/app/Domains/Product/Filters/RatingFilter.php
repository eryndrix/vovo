<?php declare(strict_types=1);

namespace App\Domains\Product\Filters;

use Illuminate\Database\Eloquent\Builder;
use App\Shared\Filter;

/**
 * @extends Filter
 */
final class RatingFilter extends Filter
{
    /**
     * Initializes the rating filter.
     * 
     * @phpstan-param float $rating
     */
    public function __construct(
        private readonly float $rating
    ) {}

    /**
     * Adds a minimum rating filter to the query builder.
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
            column: 'rating',
            operator: '>=',
            value: $this->rating
        );

        return $next($builder);
    }
}
