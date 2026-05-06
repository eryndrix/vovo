<?php declare(strict_types=1);

namespace App\Shared\ValueObjects;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Support\Str;

/**
 * @phpstan-template TSlug of UniqueSlug
 * @implements Castable
 */
abstract class UniqueSlug implements Castable
{
    /**
     * Holds the validated, non-empty slug string.
     * 
     * @phpstan-var non-empty-string
     */
    private string $slug;

    /**
     * Constructs and validates a trimmed, non-empty slug.
     *
     * @phpstan-param non-empty-string $slug
     */
    protected function __construct(string $slug)
    {
        $slug = trim(string: $slug);

        if ($slug === '') {
            throw new \InvalidArgumentException(
                message: 'Slug value cannot be empty.'
            );
        }

        $this->slug = $slug;
    }

    /**
     * Instantiates the slug object from a raw string (validated by constructor).
     * 
     * @phpstan-param string $value
     * @phpstan-return static
     */
    public static function fromString(string $value): static
    {
        return new static(slug: $value);
    }

    /**
     * Checks if this slug is exactly equal to another slug.
     *
     * @phpstan-param self $other
     * @phpstan-return bool
     */
    public function equals(self $other): bool
    {
        return $this->slug === $other->slug;
    }

    /**
     * Creates a normalized, URL-safe slug from the input string.
     *
     * @phpstan-param string $value
     * @phpstan-return static
     */
    public static function generate(string $value): static
    {
        $slug = Str::slug(title: trim(string: $value));
        return new static(slug: $slug);
    }

    /**
     * Returns the fully-qualified cast class name for Eloquent.
     *
     * @phpstan-return class-string
     */
    abstract protected static function castClassName(): string;

    /**
     * Registers this class as an Eloquent cast type.
     *
     * @phpstan-param array<int, mixed> $arguments
     * @phpstan-return class-string
     */
    public static function castUsing(array $arguments): string
    {
        return static::castClassName();
    }

    /**
     * Returns the underlying slug string.
     *
     * @phpstan-return string
     */
    public function asString(): string
    {
        return $this->slug;
    }

    /**
     * Casts the object to a string representation.
     *
     * @phpstan-return string
     */
    public function __toString(): string
    {
        return $this->asString();
    }
}
