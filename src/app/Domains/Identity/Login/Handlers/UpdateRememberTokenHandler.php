<?php declare(strict_types=1);

namespace App\Domains\Identity\Login\Handlers;

use Illuminate\Support\Str;
use App\Domains\Identity\Login\LoginContext;
use App\Shared\Result;

/**
 * @phpstan-consistent-constructor
 */
final class UpdateRememberTokenHandler
{
    /**
     * Updates the user's remember_token if "remember me" is chosen.
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

        $rememberToken = $rememberMe
            ? Str::random(length: 60)
            : null;

        // Persist the new remember_token value if user exists
        $user?->forceFill(attributes: [
            'remember_token' => $rememberToken
        ])->saveQuietly();

        return $next($context);
    }
}
