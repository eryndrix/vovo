<?php declare(strict_types=1);

namespace App\Domains\Product;

use Illuminate\Http\Response;

/**
 * @phpstan-type ProductErrorType 'invalid_filters'|'list_fetch_failed'
 */
enum ProductError: string
{
    /**
     * Indicates invalid filter parameters.
     */
    case InvalidFilters = 'invalid_filters';

    /**
     * Indicates a failure when retrieving the product list.
     */
    case ListFetchFailed = 'list_fetch_failed';

    /**
     * Gets the corresponding HTTP status code for this error.
     * 
     * @phpstan-return int
     */
    public function status(): int
    {
        return match ($this) {
            self::InvalidFilters => Response::HTTP_UNPROCESSABLE_ENTITY,
            self::ListFetchFailed => Response::HTTP_INTERNAL_SERVER_ERROR,
        };
    }

    /**
     * Gets the translation key for the error.
     * 
     * @phpstan-return string
     */
    public function message(): string
    {
        return match ($this) {
            self::InvalidFilters => 'product.invalid_filters',
            self::ListFetchFailed => 'product.list_fetch_failed',
        };
    }
}
