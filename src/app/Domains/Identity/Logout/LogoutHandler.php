<?php declare(strict_types=1);

namespace App\Domains\Identity\Logout;

use App\Shared\Result;

/**
 * @phpstan-consistent-constructor
 */
final class LogoutHandler
{
    /**
     * Handles user logout processing.
     * 
     * @phpstan-param LogoutCommand $command
     * @phpstan-return Result
     */
    public function handle(LogoutCommand $command): Result
    {
        $token = $command->user->currentAccessToken();

        // No access token found; consider logout successful
        if (!$token) {
            return Result::success(value: true);
        }

        // Token deletion failed
        if (!$token->delete()) {
            return Result::failure(
                error: LogoutError::TokenDeletionFailed
            );
        }

        // Logout successful
        return Result::success(value: true);
    }
}
