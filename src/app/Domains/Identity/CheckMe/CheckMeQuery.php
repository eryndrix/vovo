<?php declare(strict_types=1);

namespace App\Domains\Identity\CheckMe;

use Illuminate\Contracts\Auth\Authenticatable;

/**
 * @phpstan-consistent-constructor
 */
final class CheckMeQuery
{
    /**
     * Represents the currently authenticated user.
     *
     * @phpstan-param Authenticatable $user
     */
	public function __construct(
		public private(set) Authenticatable $user
	) {}
}
