<?php declare(strict_types=1);

namespace App\Domains\Identity\Login\Handlers;

use App\Domains\Identity\Login\LoginError;
use App\Domains\Identity\Login\LoginContext;
use App\Shared\Result;

/**
 * @phpstan-consistent-constructor
 */
final class CreateTokenHandler
{
    /**
     * Creates a login token and returns it in the result.
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
        $rememberMe = $context->getRememberMe();

        // Select token name based on "remember me" flag
        $tokenName = $rememberMe
            ? 'auth_token_remember'
            : 'auth_token';

        try {
            $token = $user->createToken(
                name: $tokenName,
                abilities: ['*'],
                expiresAt: $rememberMe
                    ? now()->addDays(value: 30)
                    : null
            )->plainTextToken;

            return Result::success(value: $token);
        }

        catch (\Exception $e) {
            // Return failure if token generation throws an exception
            $error = LoginError::TokenCreationFailed;
            return Result::failure(error: $error);
        }
    }
}
