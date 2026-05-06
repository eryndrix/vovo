<?php declare(strict_types=1);

namespace App\Models\Account;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Account\Id\UserId;
use App\Models\Account\Email\Email;
use App\Shared\Traits\CamelCaseAttributes;
use Laravel\Sanctum\HasApiTokens;

/**
 * @phpstan-template TUser of User
 * @extends Authenticatable
 */
final class User extends Authenticatable
{
    /**
     * Use UUIDs for primary keys.
     */
    use HasUuids;
    
    /**
     * Enables API token authentication.
     */
    use HasApiTokens;
    
    /**
     * Maps snake_case columns to camelCase attributes.
     */
    use CamelCaseAttributes;
    
    /**
     * Enables notification support.
     */
    use Notifiable;

    /**
     * Model table name.
     *
     * @phpstan-var string
     */
    protected $table = 'users';

    /**
     * Disable auto-incrementing primary key.
     *
     *  @phpstan-var bool
     */
    public $incrementing = false;

    /**
     * Primary key type.
     * 
     * @phpstan-var string
     */
    protected $keyType = 'string';

    /**
     * Attributes allowed for mass assignment.
     * 
     * @phpstan-var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * Attributes hidden during serialization.
     * 
     * @phpstan-var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute type casting definitions.
     * 
     * @phpstan-return array<string, class-string|non-empty-string>
     */
    protected function casts(): array
    {
        return [
            'id' => UserId::class,
            'email' => Email::class,
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Belongs to 'Role' relationship.
     * 
     * @phpstan-return BelongsTo<Role, User>
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(
            related: Role::class,
            foreignKey: 'role_id',
            ownerKey: 'id',
            relation: 'role'
        );
    }
}
