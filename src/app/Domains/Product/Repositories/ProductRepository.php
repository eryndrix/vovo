<?php declare(strict_types=1);

namespace App\Domains\Product\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Catalog\Product;

/**
 * @phpstan-implements ProductRepositoryInterface
 */
final class ProductRepository implements ProductRepositoryInterface
{
    /**
	 * Creates new ProductRepository instance.
	 * 
     * @phpstan-param Product $product
     */
	public function __construct(
		private readonly Product $product
	) {}

    /**
     * Returns paginated products with optional sorting, filtering, and search.
     *
     * @phpstan-param string $sort
     * @phpstan-param int $perPage
     * @phpstan-param array $filters
     * @phpstan-param string|null $q
     * 
     * @phpstan-return LengthAwarePaginator
     */
	public function paginate(
		string $sort = '',
		int $perPage = 15,
		array $filters = [],
		?string $q = null): LengthAwarePaginator
	{
		$builder = $this->product->newQuery()->with(
        	relations: 'category'
        );

        if (!empty($filters)) {
            $builder->filter(filters: $filters);
        }

        return $builder->when(
        	value: $q !== null && $q !== '',
        	callback: fn (Builder $qb) => $qb->search(
        		query: $q
        	)
        )->when(
        	value: $sort !== null && $sort !== '',
        	callback: fn (Builder $qb) => $qb->sort(
        		sort: $sort
        	)
        )->paginate(
        	perPage: $perPage
        );
	}
}
