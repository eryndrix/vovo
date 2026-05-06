<?php declare(strict_types=1);

namespace App\Domains\Identity\Login\Handlers;

use Illuminate\Support\Facades\Hash;
use App\Domains\Identity\Login\LoginContext;
use App\Domains\Identity\Login\LoginError;
use App\Shared\Result;

/**
 * @phpstan-consistent-constructor
 */
final class CheckPasswordHandler
{
    /**
     * Validates that the provided password matches the user's stored hash.
     * 
     * @phpstan-param LoginContext $context
     * @phpstan-param \Closure(LoginContext):Result $next
     * 
     * @phpstan-return Result
     */
    public function handle(
        LoginContext $context, \Closure $next): Result
    {
        $user = $context->getUser();
        $password = $context->getPassword();

        $isPasswordCorrect = Hash::check(
            value: $password,
            hashedValue: $user?->password
        );

        if (!$isPasswordCorrect) {
            $error = LoginError::InvalidPassword;
            return Result::failure(error: $error);
        }

        return $next($context);
    }
}
