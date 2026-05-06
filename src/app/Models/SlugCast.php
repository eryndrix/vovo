<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use App\Models\Account\{Role, Slug\RoleSlug};
use App\Models\Catalog\{Category, Slug\CategorySlug};
use App\Shared\ValueObjects\UniqueSlug;

/**
 * @implements CastsAttributes
 */
final class SlugCast implements CastsAttributes
{
    /**
     * Maps model classes to their corresponding Slug classes.
     * 
     * @phpstan-var array<class-string<Model>, class-string>
     */
    private const array MODEL_TO_SLUG = [
        Role::class => RoleSlug::class,
        Category::class => CategorySlug::class
    ];

    /**
     * Converts the stored string value to a Slug value object when accessing the attribute.
     * 
     * @phpstan-param Model $model
     * @phpstan-param string $key
     * @phpstan-param mixed $value
     * @phpstan-param array<string, mixed> $attributes
     * 
     * @phpstan-return object
     */
    public function get(
        Model $model,
        string $key,
        mixed $value,
        array $attributes): mixed
    {
        $class = $this->resolve(class: $model::class );
        $slug = $class::fromString(value: (string) $value);

        return $slug;
    }

    /**
     * Casts a Slug value object to its string representation for database storage.
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
        if ($value instanceof UniqueSlug) {
            return $value->asString();
        }

        $class = $this->resolve(class: $model::class);
        $slug = $class::fromString(value: (string) $value);

        return $slug->asString();
    }

    /**
     * Returns the associated Slug class for a given model class.
     * 
     * @phpstan-param class-string<Model> $class
     * @phpstan-return class-string
     */
    private function resolve(string $class): string
    {
        return self::MODEL_TO_SLUG[$class] 
            ?? throw new \RuntimeException(
                message: "No Slug class mapped for: {$class}"
            );
    }
}
