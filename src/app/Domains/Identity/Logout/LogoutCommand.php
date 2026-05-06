<?php declare(strict_types=1);

namespace App\Domains\Identity\Logout;

use Illuminate\Contracts\Auth\Authenticatable;

/**
 * @phpstan-consistent-constructor
 */
final class LogoutCommand
{
    /**
     * Authenticated user instance.
     * 
     * @phpstan-param Authenticatable $user
     */
    public function __construct(
        public private(set) Authenticatable $user
    ) {}
}
