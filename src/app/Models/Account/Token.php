<?php declare(strict_types=1);

namespace App\Models\Account;

use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @extends PersonalAccessToken
 */
final class Token extends PersonalAccessToken
{
    /**
     * Use UUIDs as primary keys.
     */
    use HasUuids;

    /**
     * Model's underlying table.
     *
     * @phpstan-var string
     */
    protected $table = 'personal_access_tokens';

    /**
     * Disable auto-increment for primary key.
     *
     *  @phpstan-var bool
     */
    public $incrementing = false;

    /**
     * Primary key data type.
     * 
     * @phpstan-var string
     */
    protected $keyType = 'string';

    /**
     * Attributes eligible for mass assignment.
     * 
     * @phpstan-var list<string>
     */
    protected $fillable = [
        'tokenable_type',
        'tokenable_id',
        'name',
        'token',
        'abilities',
        'last_used_at',
        'expires_at',
    ];

    /**
     * Polymorphic relation to the tokenable entity.
     * 
     * @phpstan-return MorphTo
     */
    public function tokenable(): MorphTo
    {
        return $this->morphTo();
    }
}
