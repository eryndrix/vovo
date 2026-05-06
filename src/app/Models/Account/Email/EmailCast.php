<?php declare(strict_types=1);

namespace App\Models\Account\Email;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * @implements CastsAttributes<Email, string>
 */
final class EmailCast implements CastsAttributes
{
    /**
     * Cast the raw database value to an Email object.
     * 
     * @phpstan-param Model $model
     * @phpstan-param string $key
     * @phpstan-param mixed $value
     * @phpstan-param array<string, mixed> $attributes
     * 
     * @phpstan-return Email
     */
    public function get(
        Model $model,
        string $key,
        mixed $value,
        array $attributes): Email
    {
        return Email::fromString(
            value: (string) $value
        );
    }

    /**
     * Transform the Email object or string into a value suitable for database storage.
     * 
     * @phpstan-param Model $model
     * @phpstan-param string $key
     * @phpstan-param mixed $value
     * @phpstan-param array<string, mixed> $attributes
     * 
     * @phpstan-return string
     */
    public function set(
        Model $model,
        string $key,
        mixed $value,
        array $attributes): string
    {
        if ($value instanceof Email) {
            return $value->asString();
        }

        return Email::fromString(
            value: (string) $value
        )->asString();
    }
}
