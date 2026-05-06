<?php declare(strict_types=1);

namespace App\Domains\Product\Repositories;

use App\Models\Catalog\Id\ProductId;
use App\Models\Catalog\Product;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @phpstan-template TModel of Product
 */
interface ProductRepositoryInterface
{
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
		?string $q = null
	): LengthAwarePaginator;
}
