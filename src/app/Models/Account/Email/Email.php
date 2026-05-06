<?php declare(strict_types=1);

namespace App\Models\Account\Email;

use Illuminate\Contracts\Database\Eloquent\Castable;

/**
 * @phpstan-implements Castable
 */
final class Email implements Castable
{
    /**
     * Stores the normalized (lowercased, trimmed) email address.
     * 
     * @phpstan-var non-empty-string
     */
    private string $email;

    /**
     * Construct and normalize the email address, validating format and length.
     * 
     * @phpstan-param non-empty-string $email
     */
    public function __construct(string $email)
    {
        self::ensureValidEmail(email: $email);

        $this->email = mb_strtolower(
            string: trim(string: $email)
        );
    }

    /**
     * Returns the EmailCast class for Eloquent casting.
     * 
     * @phpstan-param array<string,mixed> $arguments
     * @phpstan-return class-string<EmailCast>
     */
    public static function castUsing(array $arguments): string
    {
        return EmailCast::class;
    }

    /**
     * Instantiates Email from a string value.
     * 
     * @phpstan-param non-empty-string $value
     * @phpstan-return self
     */
    public static function fromString(string $value): self
    {
        return new self(email: $value);
    }

    /**
     * Returns true if two Email instances represent the same address.
     * 
     * @phpstan-param self $other
     * @phpstan-return bool
     */
    public function equals(self $other): bool
    {
        return $this->email === $other->email;
    }

    /**
     * Gets the email address as a string.
     * 
     * @phpstan-return non-empty-string
     */
    public function asString(): string
    {
        return $this->email;
    }

    /**
     * Returns the normalized email address as a string.
     * 
     * @phpstan-return non-empty-string
     */
    public function __toString(): string
    {
        return $this->asString();
    }

    /**
     * Throws if the email address is not valid or exceeds max length.
     * 
     * @phpstan-param non-empty-string $email
     * @phpstan-return void
     */
    private static function ensureValidEmail(string $email): void
    {
        if (!(bool) filter_var(
            value: $email,
            filter: FILTER_VALIDATE_EMAIL
        )) {
            throw new \InvalidArgumentException(
                message: sprintf(
                    'Invalid Email Format: "%s"', $email)
            );
        }
        
        self::ensureMaxLength(email: $email);
    }
    
    /**
     * Validates that the email address does not exceed 254 characters.
     * 
     * @phpstan-param non-empty-string $email
     * @phpstan-return void
     */
    private static function ensureMaxLength(string $email): void
    {
        if (mb_strlen(string: $email, encoding: 'UTF-8') > 254) {
            throw new \InvalidArgumentException(
                message: sprintf(
                    'Email length exceeds 254 characters: "%s"',
                    $email
                )
            );
        }
    }
}
