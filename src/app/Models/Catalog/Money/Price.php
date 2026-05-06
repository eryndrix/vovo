<?php declare(strict_types=1);

namespace App\Models\Catalog\Money;

use App\Shared\ValueObjects\Money;

/**
 * @phpstan-extends Money
 */
final class Price extends Money
{
    /**
     * Price value stored in cents.
     * 
     * @phpstan-var int
     */
    private int $priceInCents;

    /**
     * Constructs a Price object, validating cents value.
     * 
     * @phpstan-param int $cents
     */
    private function __construct(int $cents)
    {
        if ($cents < 0) {
            throw new \InvalidArgumentException(
                message: 'Price cannot be negative.'
            );
        }

        if ($cents > 999_999_999_99) {
            throw new \InvalidArgumentException(
                message: 'Price too large.'
            );
        }

        $this->priceInCents = $cents;
    }
    
    /**
     * Returns the value caster class used for serialization.
     * 
     * @phpstan-return class-string
     */
    protected static function castClassName(): string
    {
        return MoneyCast::class;
    }

    /**
     * Instantiates a Price from a cent value.
     * 
     * @phpstan-param int $value
     * @phpstan-return static
     */
    public static function fromCents(int $value): static
    {
        return new static(cents: $value);
    }

    /**
     * Gets the price as cents.
     * 
     * @phpstan-return int
     */
    public function valueInCents(): int
    {
        return $this->priceInCents;
    }
}
