<?php declare(strict_types=1);

namespace App\Models\Catalog\Money;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * @implements CastsAttributes<Price, int>
 */
final class MoneyCast implements CastsAttributes
{
    /**
     * Cast the database value to a Price instance.
     * 
     * @phpstan-param Model $model
     * @phpstan-param string $key
     * @phpstan-param mixed $value
     * @phpstan-param array<string, mixed> $attributes
     * 
     * @phpstan-return Price
     */
    public function get(
        Model $model,
        string $key,
        mixed $value,
        array $attributes): Price
    {
        return Price::fromCents(value: (int) $value);
    }

    /**
     * Prepare the value for storage as an integer (cents).
     * 
     * @phpstan-param Model $model
     * @phpstan-param string $key
     * @phpstan-param mixed $value
     * @phpstan-param array<string, mixed> $attributes
     * 
     * @phpstan-return int
     */
    public function set(
        Model $model,
        string $key,
        mixed $value,
        array $attributes): int
    {
         if ($value instanceof Price) {
            return $value->valueInCents();
        }

        if (is_float(value: $value)
            || is_numeric(value: $value)) {
            return (int) round(
                num: (float) $value * 100
            );
        }

        return (int) $value;
    }
}
