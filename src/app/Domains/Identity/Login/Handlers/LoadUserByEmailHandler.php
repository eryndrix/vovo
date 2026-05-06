<?php declare(strict_types=1);

namespace App\Domains\Identity\Login\Handlers;

use App\Domains\Identity\Login\LoginError;
use App\Domains\Identity\UserRepositoryInterface;
use App\Domains\Identity\Login\LoginContext;
use App\Shared\Result;

final class LoadUserByEmailHandler
{
    /**
     * Injects the user repository used to retrieve user accounts.
     * 
     * @phpstan-param UserRepositoryInterface $user
     */
    public function __construct(
        private UserRepositoryInterface $user
    ) {}

    /**
     * Retrieves the user by email and updates the context with the user instance.
     * 
     * @phpstan-param LoginContext $context
     * @phpstan-param \Closure(LoginContext):Result $next
     * 
     * @phpstan-return Result
     */
    public function handle(
        LoginContext $context, \Closure $next): Result
    {
        $user = $this->user->findByEmail(
            email: $context->getEmail()
        );

        if (!$user) {
            $error = LoginError::UserNotFound;
            return Result::failure(error: $error);
        };

        $context = $context->withUser(user: $user);

        return $next($context);
    }
}
