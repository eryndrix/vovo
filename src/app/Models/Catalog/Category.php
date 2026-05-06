<?php declare(strict_types=1);

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Shared\Traits\CamelCaseAttributes;
use App\Models\Catalog\Id\CategoryId;
use App\Models\Catalog\Slug\CategorySlug;

/**
 * @phpstan-template TProduct of Product
 * @extends Model
 */
final class Category extends Model
{
    /**
     * Use UUIDs as primary keys.
     */
    use HasUuids;

    /**
     * Map snake_case columns to camelCase attributes.
     */
    use CamelCaseAttributes;
    
    /**
     * Enables model factory integration for tests and seeding.
     */
    use HasFactory;

    /**
     * The database table for this model.
     *
     * @phpstan-var string
     */
    protected $table = 'categories';

    /**
     * Auto-incrementing primary keys are disabled.
     *
     * @phpstan-var bool
     */
    public $incrementing = false;

    /**
     * Primary key type is string (UUID).
     * 
     * @phpstan-var string
     */
    protected $keyType = 'string';

    /**
     * Attributes that can be mass assigned.
     *
     * @phpstan-var list<string>
     */
    protected $fillable = ['name', 'slug'];

    /**
     * Attribute type casts.
     * 
     * @phpstan-return array<string, class-string|non-empty-string>
     */
    protected function casts(): array
    {
        return [
            'id' => CategoryId::class,
            'slug' => CategorySlug::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Returns a new factory instance for this model.
     * 
     * @phpstan-return Factory<Category>
     */
    protected static function newFactory(): Factory
    {
        return \Database\Factories\CategoryFactory::new();
    }

    /**
     * Defines the one-to-many relationship to products.
     * 
     * @phpstan-return HasMany<Product>
     */
    public function products(): HasMany
    {
        return $this->hasMany(
            related: Product::class,
            foreignKey: 'role_id',
            localKey: 'id'
        );
    }
}
