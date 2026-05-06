<?php declare(strict_types=1);

namespace App\Domains\Identity\CheckMe;

use Illuminate\Http\Response as Status;

/**
 * @phpstan-type CheckMeError 'not_authenticated'
 */
enum CheckMeError: string
{
    /**
     * Indicates the user is not authenticated.
     */
    case NotAuthenticated = 'not_authenticated';

    /**
     * Gets the associated HTTP status code.
     * 
     * @phpstan-return int
     */
    public function status(): int
    {
        return Status::HTTP_UNAUTHORIZED;
    }

    /**
     * Gets the translation key for this error.
     * 
     * @phpstan-return string
     */
    public function message(): string
    {
        return 'auth.not_authenticated';
    }
}
