<?php declare(strict_types=1);

namespace App\Models;

use App\Shared\ValueObjects\UniqueId;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use App\Models\Catalog\{Category, Product};
use App\Models\Catalog\Id\{CategoryId, ProductId};
use App\Models\Account\{Role, User};
use App\Models\Account\Id\{RoleId, UserId};

/**
 * @implements CastsAttributes
 */
final class IdCast implements CastsAttributes
{
    /**
     * Mapping of model classes to their associated ID value object classes.
     * 
     * @phpstan-var array<class-string<Model>, class-string>
     */
    private const array MODEL_TO_ID = [
        Role::class => RoleId::class,
        User::class => UserId::class,
        Category::class => CategoryId::class,
        Product::class => ProductId::class
    ];

    /**
     * Converts the stored string to an ID value object when reading from the database.
     * 
     * @phpstan-param Model $model
     * @phpstan-param string $key
     * @phpstan-param mixed $value
     * @phpstan-param array<string, mixed> $attributes
     * 
     * @phpstan-return UniqueId
     */
    public function get(
        Model $model,
        string $key,
        mixed $value,
        array $attributes): UniqueId
    {
        $class = $this->resolve(class: $model::class);
        $id = $class::fromString(value: (string) $value);
        
        return $id;
    }

    /**
     * Serializes an ID value object to its string representation for storage.
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
        if ($value instanceof UniqueId) {
            return $value->asString();
        }

        $class = $this->resolve(class: $model::class);
        $id = $class::fromString(value: (string) $value);

        return $id->asString();
    }

    /**
     * Returns the ID value object class for the specified model.
     * 
     * @phpstan-param class-string<Model> $class
     * @phpstan-return class-string
     */
    private function resolve(string $class): string
    {
        return self::MODEL_TO_ID[$class] 
            ?? throw new \RuntimeException(
                message: "ID mapping missing for: {$class}"
            );
    }
}
