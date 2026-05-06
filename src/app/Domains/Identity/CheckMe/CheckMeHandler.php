<?php declare(strict_types=1);

namespace App\Domains\Identity\CheckMe;

use App\Models\Account\User;
use App\Shared\Result;

/**
 * @phpstan-type TQuery CheckMeQuery
 * @phpstan-type TResult Result
 */
final class CheckMeHandler
{
    /**
     * Returns the current user instance if authenticated; otherwise, returns an authentication error.
     * 
     * @phpstan-param CheckMeQuery $query
     * @phpstan-return Result
     */
    public function handle(CheckMeQuery $query): Result
    {
        if (!($query->user instanceof User)) {
            return Result::failure(
                error: CheckMeError::NotAuthenticated
            );
        }

        return Result::success(value: $query->user);
    }
}
