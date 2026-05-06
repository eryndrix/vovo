<?php declare(strict_types=1);

namespace App\Domains\Product\Filters;

use App\Models\Catalog\Money\Price;
use Illuminate\Database\Eloquent\Builder;
use App\Shared\Filter;

/**
 * @extends Filter
 */
final class PriceFilter extends Filter
{
    /**
     * Initializes the price range filter.
     * 
     * @phpstan-param Price|null $min
     * @phpstan-param Price|null $max
     */
    public function __construct(
        private readonly ?Price $min = null,
        private readonly ?Price $max = null
    ) {}

    /**
     * Add price filtering constraints to the query builder.
     * 
     * @phpstan-param Builder $builder
     * @phpstan-param \Closure $next
     * 
     * @phpstan-return Builder
     */
    public function __invoke(
        Builder $builder, \Closure $next): Builder
    {
        $builder->when(
            value: $this->min !== null,
            callback: function (Builder $q): Builder {
                $q->where(
                    column: 'price',
                    operator: '>=',
                    value: $this->min->valueInCents()
                );

                return $q;
            }
        )->when(
            value: $this->max !== null,
            callback: function (Builder $q): Builder {
                $q->where(
                    column: 'price',
                    operator: '<=',
                    value: $this->max->valueInCents()
                );

                return $q;
            }
        );

        return $next($builder);
    }
}
