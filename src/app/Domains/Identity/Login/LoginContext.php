<?php declare(strict_types=1);

namespace App\Domains\Identity\Login;

use App\Shared\Context;
use App\Models\Account\Email\Email;
use App\Models\Account\User;

/**
 * @phpstan-template TCommand of LoginCommand
 * @phpstan-template TUser of User|null
 */
final class LoginContext extends Context
{
    /**
     * Initializes a new instance of LoginContext.
     * 
     * @phpstan-param LoginCommand $command
     * @phpstan-param User|null $user
     */
    public function __construct(
        private LoginCommand $command,
        private ?User $user = null
    ) {}

    /**
     * Instantiates LoginContext from a command.
     * 
     * @phpstan-param LoginCommand $command
     * @phpstan-return static
     */
    public static function of(
        LoginCommand $command): static
    {
        return new static(command: $command);
    }

    /**
     * Retrieves the email value from the login command.
     * 
     * @phpstan-return Email
     */
    public function getEmail(): Email
    {
        return Email::fromString(
            value: $this->command->email
        );
    }

    /**
     * Retrieves the password from the login command.
     * 
     * @phpstan-return string
     */
    public function getPassword(): string
    {
        return $this->command->password;
    }

    /**
     * Indicates if the "remember me" option was selected.
     * 
     * @phpstan-return bool
     */
    public function getRememberMe(): bool
    {
        return $this->command->rememberMe;
    }

    /**
     * Returns a new context with the specified user.
     * 
     * @phpstan-param User $user
     * @phpstan-return static
     */
    public function withUser(User $user): static
    {
        $clone = clone $this;
        $clone->user = $user;

        return $clone;
    }

    /**
     * Returns the current user, or null if not set.
     * 
     * @phpstan-return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }
}
