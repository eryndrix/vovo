<?php declare(strict_types=1);

namespace App\Shared\Traits;

use Illuminate\Support\Str;

/**
 * @phpstan-template TModel of object
 */
trait CamelCaseAttributes
{
    /**
     * getAttribute override to provide camelCase support.
     * 
     * @phpstan-param string $key
     * @phpstan-return mixed
     */
    public function getAttribute($key)
    {
        // Check if key is a relation and return it if present
        if (array_key_exists($key, $this->relations)) {
            return $this->relations[$key];
        }

        $snakeKey = Str::snake(value: $key);

        // Resolve attribute by snake_case if it exists
        if ($this->hasAttribute(key: $snakeKey)) {
            return parent::getAttribute(
                key: $snakeKey
            );
        }

        // Fallback to default lookup
        return parent::getAttribute(key: $key);
    }

    /**
     * setAttribute override to provide camelCase support.
     * 
     * @phpstan-param string $key
     * @phpstan-param mixed $value
     * 
     * @phpstan-return $this
     */
    public function setAttribute($key, $value)
    {
        $snakeKey = Str::snake(value: $key);

        // Set attribute using snake_case key if present
        if ($this->hasAttribute(key: $snakeKey)) {
            return parent::setAttribute(
                key: $snakeKey,
                value: $value
            );
        }

        // Always fall back to snake_case assignment for new/unknown attributes
        return parent::setAttribute(
            key: $snakeKey, value: $value);
    }

    /**
     * Returns true if the given snake_case key matches an attribute, cast, or accessor.
     * 
     * @phpstan-param string $key
     * @phpstan-return bool
     */
    public function hasAttribute($key)
    {
        return array_key_exists(
            key: $key,
            array: $this->attributes
        ) || array_key_exists(
            key: $key,
            array: $this->casts
        ) || method_exists(
            object_or_class: $this,
            method: Str::camel(value: $key)
        );
    }
}