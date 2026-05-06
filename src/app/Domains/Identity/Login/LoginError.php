<?php declare(strict_types=1);

namespace App\Domains\Identity\Login;

use Illuminate\Http\Response;

/**
 * @phpstan-type LoginError string
 */
enum LoginError: string
{
    /**
     * User record not found.
     */
    case UserNotFound = 'user_not_found';

    /**
     * Password does not match.
     */
    case InvalidPassword = 'invalid_password';

    /**
     * Failed to create access token.
     */
    case TokenCreationFailed = 'token_creation_failed';

    /**
     * Login attempts exceeded allowed limit.
     */
    case TooManyLoginAttempts = 'too_many_login_attempts';

    /**
     * Returns the HTTP status code for the error.
     * 
     * @phpstan-return int
     */
    public function status(): int
    {
        return match ($this) {
            self::UserNotFound, self::InvalidPassword => Response::HTTP_UNAUTHORIZED,
            self::TooManyLoginAttempts => Response::HTTP_TOO_MANY_REQUESTS,
            self::TokenCreationFailed => Response::HTTP_INTERNAL_SERVER_ERROR,
        };
    }

    /**
     * Returns the translation key for the error message.
     * 
     * @phpstan-return string
     */
    public function message(): string
    {
        return match ($this) {
            self::UserNotFound, self::InvalidPassword => 'auth.invalid_credentials',
            self::TooManyLoginAttempts => 'auth.throttle',
            self::TokenCreationFailed => 'auth.token_creation_failed',
        };
    }
}
