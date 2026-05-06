<?php declare(strict_types=1);

namespace App\Domains\Product\Values;

use App\Models\Catalog\Money\Price;

/**
 * @phpstan-consistent-constructor
 */
final readonly class PriceRange
{
    /**
     * Initializes a price range with optional minimum and maximum prices.
     * 
     * @phpstan-param Price|null $from
     * @phpstan-param Price|null $to
     */
    public function __construct(
        public private(set) ?Price $from = null,
        public private(set) ?Price $to = null
    ) {
        // Enforce: minimum price must not exceed maximum price
        if ($from && $to
            && $from->valueInCents() > $to->valueInCents()
        ) {
            throw new \InvalidArgumentException(
                message: 'Min price cannot exceed max price.'
            );
        }
    }
}
