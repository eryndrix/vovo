<?php declare(strict_types=1);

namespace App\Domains\Product\Values;

/**
 * @phpstan-consistent-constructor
 */
final readonly class Rating
{
    /**
     * Initializes a rating value constrained to the 0–5 range.
     * 
     * @phpstan-param float $value
     */
    public function __construct(
        public private(set) float $value
    ) {
        if ($this->value < 0.0 || $this->value > 5.0) {
            throw new \InvalidArgumentException(
                message: 'Rating must be between 0 and 5.'
            );
        }
    }
}
