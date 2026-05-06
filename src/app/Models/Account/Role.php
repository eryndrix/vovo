<?php declare(strict_types=1);

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Shared\Traits\CamelCaseAttributes;
use App\Models\Account\Id\RoleId;
use App\Models\Account\Slug\RoleSlug;

/**
 * @extends Model
 */
final class Role extends Model
{
    /**
     * Use UUIDs for primary keys.
     */
    use HasUuids;

    /**
     * Provides camelCase accessors for snake_case columns.
     */
    use CamelCaseAttributes;

    /**
     * Model table name.
     *
     * @phpstan-var string
     */
    protected $table = 'roles';

    /**
     * Disable auto-incrementing primary keys.
     *
     * @phpstan-var bool
     */
    public $incrementing = false;

    /**
     * Primary key column type.
     * 
     * @phpstan-var string
     */
    protected $keyType = 'string';

    /**
     * Attributes permitted for mass assignment.
     * 
     * @phpstan-var list<string>
     */
    protected $fillable = ['name', 'slug'];

    /**
     * Attribute cast definitions.
     * 
     * @phpstan-return array<string, class-string|non-empty-string>
     */
    protected function casts(): array
    {
        return [
            'id' => RoleId::class,
            'slug' => RoleSlug::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Relationship: Role has many users.
     * 
     * @phpstan-return HasMany<User>
     */
    public function users(): HasMany
    {
        return $this->hasMany(
            related: User::class,
            foreignKey: 'role_id',
            localKey: 'id'
        );
    }
}
