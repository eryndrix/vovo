<?php declare(strict_types=1);

namespace App\Domains\Product;

use App\Domains\Product\Repositories\ProductRepositoryInterface;
use App\Shared\Result;

/**
 * @phpstan-template TEntity
 */
final class ProductHandler
{
    /**
     * Initializes the product handler with a repository dependency.
     * 
     * @phpstan-param ProductRepositoryInterface $repository
     */
    public function __construct(
        public ProductRepositoryInterface $repository
    ) {}

    /**
     * Executes the provided product query and returns the result.
     * 
     * @phpstan-param ProductQuery $query
     * @phpstan-return Result
     */
    public function handle(ProductQuery $query): Result
    {
        try {
            $products = $this->repository->paginate(
                sort: $query->sort?->value ?? 'newest',
                perPage: $query->perPage?->value,
                filters: FilterFactory::fromQuery(query: $query),
                q: $query->q ?? null
            );
            
            return Result::success(value: $products);
        }

        catch (\InvalidArgumentException $e) {
            $invalidFilters = ProductError::InvalidFilters;
            return Result::failure(error: $invalidFilters);
        }

        catch (\Throwable $e) {
            $listFetchFailed = ProductError::ListFetchFailed;
            return Result::failure(error: $listFetchFailed);
        }
    }
}
