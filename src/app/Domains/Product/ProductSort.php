<?php declare(strict_types=1);

namespace App\Domains\Product;

/**
 * @phpstan-template TValue of string
 * @phpstan-consistent-constructor
 * @phpstan-extends \BackedEnum<TValue>
 */
enum ProductSort: string
{
    /**
     * Sort by price from low to high.
     */
    case PRICE_ASC = 'price_asc';

    /**
     * Sort by price from high to low.
     */
    case PRICE_DESC = 'price_desc';

    /**
     * Sort by rating from high to low.
     */
    case RATING_DESC = 'rating_desc';

    /**
     * Sort by newest first.
     */
    case NEWEST = 'newest';

    /**
     * Returns all enum values as a list.
     * 
     * @phpstan-return list<string>
     */
    public static function casesValues(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Returns a human-readable label for the sort option.
     * 
     * @phpstan-return string
     */
    public function label(): string
    {
        return match($this) {
            self::PRICE_ASC => 'Price ascending',
            self::PRICE_DESC => 'Price descending', 
            self::RATING_DESC => 'By rating',
            self::NEWEST => 'Newest',
        };
    }
    
    /**
     * Checks if the sort option is price ascending.
     * 
     * @phpstan-return bool
     */
    public static function isPriceAsc(): bool
    {
        return $this === self::PRICE_ASC;
    }
    
    /**
     * Checks if the sort option is price descending.
     * 
     * @phpstan-return bool
     */
    public static function isPriceDesc(): bool
    {
        return $this === self::PRICE_DESC;
    }
    
    /**
     * Checks if the sort option is rating descending.
     * 
     * @phpstan-return bool
     */
    public static function isRatingDesc(): bool
    {
        return $this === self::RATING_DESC;
    }
    
    /**
     * Checks if the sort option is newest first.
     * 
     * @phpstan-return bool
     */
    public static function isNewest(): bool
    {
        return $this === self::NEWEST;
    }
}
