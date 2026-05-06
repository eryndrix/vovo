<?php declare(strict_types=1);

namespace App\Domains\Identity;

use App\Models\Account\Id\UserId;
use App\Models\Account\User;
use App\Models\Account\Email\Email;

/**
 * @phpstan-template TModel of User
 */
interface UserRepositoryInterface
{
    /**
     * Finds a user by their unique identifier.
     * 
     * @phpstan-param UserId $id
     * @phpstan-return User|null
     */
    public function findById(UserId $id): ?User;

    /**
     * Finds a user by their email address.
     * 
     * @phpstan-param Email $email
     * @phpstan-return User|null
     */
    public function findByEmail(Email $email): ?User;
}
