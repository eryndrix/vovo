<?php declare(strict_types=1);

namespace App\Domains\Product\Filters;

use App\Models\Catalog\Id\CategoryId;
use Illuminate\Database\Eloquent\Builder;
use App\Shared\Filter;

/**
 * @extends Filter
 */
final class CategoryFilter extends Filter
{
    /**
     * Category filter constructor.
     * 
     * @phpstan-param CategoryId $categoryId
     */
    public function __construct(
        private readonly CategoryId $categoryId
    ) {}

    /**
     * Add category filter constraint to the Eloquent query.
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
            column: 'category_id',
            operator: '=',
            value: $this->categoryId->asString()
        );

        return $next($builder);
    }
}
