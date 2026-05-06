<?php declare(strict_types=1);

namespace App\Models\Catalog\Money;

enum Currency: string
{
    /**
     * Russian ruble currency identifier.
     */
    case RUB = 'RUB';

    /**
     * Euro currency identifier.
     */
    case EUR = 'EUR';

    /**
     * US dollar currency identifier.
     */
    case USD = 'USD';

    /**
     * Get the currency symbol.
     *
     * @phpstan-return string
     */
    public function symbol(): string
    {
        return match($this) {
            self::USD => '$',
            self::EUR => '€',
            self::RUB => '₽',
        };
    }

    /**
     * Get human-readable currency label.
     *
     * @phpstan-return string
     */
    public function label(): string
    {
        return match($this) {
            self::RUB => 'Russian ruble',
            self::EUR => 'Euro',
            self::USD => 'US dollar',
        };
    }

    /**
     * Check if the currency is EUR.
     *
     * @phpstan-return bool
     */
    public function isEur(): bool
    {
        return $this === self::EUR;
    }

    /**
     * Check if the currency is RUB.
     *
     * @phpstan-return bool
     */
    public function isRub(): bool
    {
        return $this === self::RUB;
    }

    /**
     * Check if the currency is USD.
     *
     * @phpstan-return bool
     */
    public function isUsd(): bool
    {
        return $this === self::USD;
    }
}
