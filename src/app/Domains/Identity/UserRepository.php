<?php declare(strict_types=1);

namespace App\Domains\Identity;

use App\Models\Account\Id\UserId;
use App\Models\Account\User;
use App\Models\Account\Email\Email;

/**
 * @implements UserRepositoryInterface
 */
final class UserRepository implements UserRepositoryInterface
{
	/**
	 * Creates new UserRepository instance.
	 * 
	 * @phpstan-var User
	 */
	public function __construct(
		private readonly User $user
	) {}

	/**
	 * Finds user by unique ID using Eloquent query.
	 * 
	 * @phpstan-param UserId $id
	 * @phpstan-return User|null
	 */
	public function findById(UserId $id): ?User
	{
		return $this->user->newQuery()->find(
			id: $id
		);
	}

	/**
	 * Finds user by email address using Eloquent where clause.
	 * 
	 * @phpstan-param Email $email
	 * @phpstan-return User|null
	 */
	public function findByEmail(Email $email): ?User
	{
		return $this->user->newQuery()->where(
			column: 'email',
			operator: '=',
			value: $email
		)->first();
	}
}
