<?php declare(strict_types=1);

namespace App\Domains\Identity\Login\Handlers;

use App\Domains\Identity\Login\LoginError;
use App\Domains\Identity\Login\LoginContext;
use Illuminate\Support\Facades\RateLimiter;
use App\Shared\Result;

final class RateLimitHandler
{
    /**
     * Prefix for login rate limit keys.
     */
    private const string RATE_LIMIT_KEY = 'login:';

    /**
     * Allowed login attempts per minute.
     */
    private const int ATTEMPTS_PER_MINUTE = 5;

    /**
     * Enforces rate limiting for login attempts.
     * 
     * @phpstan-param LoginContext $context
     * @phpstan-param \Closure(LoginContext):Result $next
     * 
     * @phpstan-return Result
     */
    public function handle(
        LoginContext $context, \Closure $next): Result 
    {
        $email = $context->getEmail()->asString();
        $key = self::RATE_LIMIT_KEY . md5(string: $email);

        if (RateLimiter::tooManyAttempts(key: $key, 
            maxAttempts: self::ATTEMPTS_PER_MINUTE
        )) {
            $error = LoginError::TooManyLoginAttempts;
            return Result::failure(error: $error);
        }

        RateLimiter::hit(key: $key, decaySeconds: 60);

        return $next($context);
    }
}
