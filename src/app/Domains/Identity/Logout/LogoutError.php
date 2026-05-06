<?php declare(strict_types=1);

namespace App\Domains\Identity\Logout;

use Illuminate\Http\Response;

/**
 * @phpstan-type LogoutError string
 */
enum LogoutError: string
{
    /**
     * Failed to delete the access token.
     */
    case TokenDeletionFailed = 'token_deletion_failed';

    /**
     * Returns the HTTP status code for the error.
     * 
     * @phpstan-return int
     */
    public function status(): int
    {
        return match ($this) {
            self::TokenDeletionFailed => Response::HTTP_INTERNAL_SERVER_ERROR,
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
            self::TokenDeletionFailed => 'auth.logout_failed',
        };
    }
}
