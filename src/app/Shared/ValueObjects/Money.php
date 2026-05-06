<?php declare(strict_types=1);

namespace App\Shared\ValueObjects;

use Illuminate\Contracts\Database\Eloquent\Castable;

/**
 * @implements Castable
 */
abstract class Money implements Castable
{
    /**
     * Gets the value in cents.
     *
     * @phpstan-return int
     */
    abstract public function valueInCents(): int;

    /**
     * Instantiates from a cent value.
     *
     * @phpstan-param int $value
     * @phpstan-return static
     */
    abstract public static function fromCents(int $value): static;

    /**
     * Returns the class name to use for Eloquent casting.
     *
     * @phpstan-return class-string
     */
    abstract protected static function castClassName(): string;

    /**
     * Registers this type for Eloquent attribute casting.
     *
     * @phpstan-param array<array-key, mixed> $arguments
     * @phpstan-return class-string
     */
    public static function castUsing(array $arguments): string
    {
        return static::castClassName();
    }

    /**
     * Instantiates from a decimal (principal) value.
     *
     * @phpstan-param float $value
     * @phpstan-return static
     */
    public static function fromPrincipal(float $value): static
    {
        $cents = (int) round(num: $value * 100);

        return static::fromCents(value: $cents);
    }

    /**
     * Checks for exact value equality with another instance.
     *
     * @phpstan-param self $other
     * @phpstan-return bool
     */
    public function equals(self $other): bool
    {
        return $this->valueInCents() === $other->valueInCents();
    }

    /**
     * Gets the monetary value as a decimal.
     *
     * @phpstan-return float
     */
    public function value(): float
    {
        return $this->valueInCents() / 100.0;
    }

    /**
     * Returns a formatted string representation.
     *
     * @phpstan-return string
     */
    public function formatted(): string
    {
        return number_format(
            num: $this->value(),
            decimals: 2,
            decimal_separator: ',',
            thousands_separator: ' '
        );
    }

    /**
     * Casts the value to a string.
     *
     * @phpstan-return string
     */
    public function __toString(): string
    {
        return $this->formatted();
    }
}
