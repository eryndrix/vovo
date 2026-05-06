<?php declare(strict_types=1);

namespace App\Shared\ValueObjects;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Support\Str;

/**
 * @phpstan-template TSelf of UniqueId
 * @implements Castable
 */
abstract class UniqueId implements Castable
{
    /**
     * Stores a non-empty UUID v7 string.
     * 
     * @phpstan-var non-empty-string
     */
    private string $id;

    /**
     * Constructs with validation of UUID v7 format.
     *
     * @phpstan-param non-empty-string $id
     */
    protected function __construct(string $id)
    {
        if (!Str::isUuid(value: trim(string: $id), version: 7)) {
            throw new \InvalidArgumentException(
                message: "Value '{$id}' is not a valid UUID v7."
            );
        }

        $this->id = $id;
    }

    /**
     * Instantiates the object from a raw string (validated by constructor).
     * 
     * @phpstan-param non-empty-string $value
     * @phpstan-return static
     */
    public static function fromString(string $value): static
    {
        return new static(id: $value);
    }

    /**
     * Checks if this ID is identical to another.
     *
     * @phpstan-param self $other
     * @phpstan-return bool
     */
    public function equals(self $other): bool
    {
        return $this->id === $other->id;
    }

    /**
     * Generates and returns a new UUID v7 instance.
     *
     * @phpstan-return static
     */
    public static function generate(): static
    {
        return new static(id: Str::uuid7()->toString());
    }

    /**
     * Returns the fully-qualified Eloquent cast class name.
     *
     * @phpstan-return class-string
     */
    abstract protected static function castClassName(): string;

    /**
     * Registers this class for Eloquent attribute casting.
     *
     * @phpstan-param array<array-key, mixed> $arguments
     * @phpstan-return class-string
     */
    public static function castUsing(array $arguments): string
    {
        return static::castClassName();
    }

    /**
     * Returns the raw UUID value.
     *
     * @phpstan-return non-empty-string
     */
    public function asString(): string
    {
        return $this->id;
    }

    /**
     * Returns the UUID as a string.
     *
     * @phpstan-return non-empty-string
     */
    public function __toString(): string
    {
        return $this->asString();
    }
}
