<?php declare(strict_types=1);

namespace App\Domains\Product;

use App\Domains\Product\Values\Rating;
use App\Domains\Product\Values\PriceRange;
use App\Domains\Product\Values\PerPage;
use App\Models\Catalog\Money\Price;
use App\Models\Catalog\Id\CategoryId;
use App\Http\Requests\ProductRequest;

/**
 * @phpstan-consistent-constructor
 */
final readonly class ProductQuery
{
    /**
     * Encapsulates product filters and pagination settings.
     *
     * @phpstan-param PerPage $perPage
     * @phpstan-param string|null $q
     * @phpstan-param ProductSort|null $sort
     * @phpstan-param PriceRange|null $price
     * @phpstan-param CategoryId|null $categoryId
     * @phpstan-param Rating|null $rating
     * @phpstan-param bool|null $inStock
     */
    public function __construct(
        public private(set) PerPage $perPage,
        public private(set) ?string $q,
        public private(set) ?ProductSort $sort,
        public private(set) ?PriceRange $price,
        public private(set) ?CategoryId $categoryId,
        public private(set) ?Rating $rating,
        public private(set) ?bool $inStock,
    ) {}

    /**
     * Instantiates ProductQuery using validated request data.
     *
     * @phpstan-param ProductRequest $request
     * @phpstan-return self
     */
    public static function fromRequest(ProductRequest $request): self
    {
        $data = $request->validatedData();

        return new self(
            perPage: new PerPage(value: (int) $data['per_page'] ?? 15),
            q: $data['q'],
            sort: self::sort(value: $data['sort']),
            price: self::priceRange(
                from: $data['price_from'],
                to: $data['price_to']
            ),
            categoryId: self::categoryId(value: $data['category_id']),
            rating: self::rating(value: $data['rating_from']),
            inStock: $data['in_stock'],
        );
    }

    /**
     * Maps a string sort value to a ProductSort instance or null.
     *
     * @phpstan-param string|null $value
     * @phpstan-return ProductSort|null
     */
    private static function sort(?string $value): ?ProductSort
    {
        return $value !== null ? ProductSort::from(value: $value) : null;
    }

    /**
     * Constructs a PriceRange from given minimum and maximum price values.
     *
     * @phpstan-param float|null $from
     * @phpstan-param float|null $to
     *
     * @phpstan-return PriceRange|null
     */
    private static function priceRange(?float $from, ?float $to): ?PriceRange
    {
        if ($from === null && $to === null) {
            return null;
        }

        return new PriceRange(
            from: $from !== null
                ? Price::fromPrincipal(value: $from)
                : null,
            to: $to !== null
                ? Price::fromPrincipal(value: $to)
                : null
        );
    }

    /**
     * Converts a category ID string to a CategoryId instance or null.
     *
     * @phpstan-param string|null $value
     * @phpstan-return CategoryId|null
     */
    private static function categoryId(?string $value): ?CategoryId
    {
        return $value !== null ? CategoryId::fromString(value: $value) : null;
    }

    /**
     * Creates a Rating instance from the provided minimum value, or null.
     *
     * @phpstan-param float|null $value
     * @phpstan-return Rating|null
     */
    private static function rating(?float $value): ?Rating
    {
        return $value !== null ? new Rating(value: $value) : null;
    }
}
