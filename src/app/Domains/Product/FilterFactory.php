<?php declare(strict_types=1);

namespace App\Domains\Product;

use App\Domains\Product\Filters\CategoryFilter;
use App\Domains\Product\Filters\PriceFilter;
use App\Domains\Product\Filters\RatingFilter;
use App\Domains\Product\Filters\StockFilter;

/**
 * @phpstan-template TFilter of object
 */
final class FilterFactory
{
    /**
     * Builds filter objects based on the provided ProductQuery.
     * 
     * @phpstan-param ProductQuery $query
     * @phpstan-return list<object>
     */
    public static function fromQuery(ProductQuery $query): array
    {
        $filters = [];

        self::addCategoryFilter(filters: $filters, query: $query);
        self::addPriceFilter(filters: $filters, query: $query);
        self::addRatingFilter(filters: $filters, query: $query);
        self::addStockFilter(filters: $filters, query: $query);

        return $filters;
    }

    /**
     * Appends a CategoryFilter if a category ID is specified.
     * 
     * @phpstan-param list<object> &$filters
     * @phpstan-param ProductQuery $query
     */
    private static function addCategoryFilter(
        array &$filters, ProductQuery $query): void
    {
        if ($query->categoryId === null) {
            return;
        }

        $filters[] = new CategoryFilter(categoryId: $query->categoryId);
    }

    /**
     * Appends a PriceFilter if a price range is provided.
     * 
     * @phpstan-param list<object> &$filters
     * @phpstan-param ProductQuery $query
     */
    private static function addPriceFilter(
        array &$filters, ProductQuery $query): void
    {
        if ($query->price?->from === null && $query->price?->to === null) {
            return;
        }

        $filters[] = new PriceFilter(
            min: $query->price?->from,
            max: $query->price?->to
        );
    }

    /**
     * Appends a RatingFilter if a rating minimum is set.
     * 
     * @phpstan-param list<object> &$filters
     * @phpstan-param ProductQuery $query
     */
    private static function addRatingFilter(
        array &$filters, ProductQuery $query): void
    {
        if ($query->rating === null) {
            return;
        }

        $filters[] = new RatingFilter(rating: $query->rating?->value);
    }

    /**
     * Appends a StockFilter if stock status is specified.
     * 
     * @phpstan-param list<object> &$filters
     * @phpstan-param ProductQuery $query
     */
    private static function addStockFilter(
        array &$filters, ProductQuery $query): void
    {
        if ($query->inStock === null) {
            return;
        }

        $filters[] = new StockFilter(inStock: $query->inStock);
    }
}
