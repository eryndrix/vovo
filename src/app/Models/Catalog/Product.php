<?php declare(strict_types=1);

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use App\Shared\Traits\Filterable;
use App\Shared\Traits\CamelCaseAttributes;
use App\Models\Catalog\Money\Price;
use App\Models\Catalog\Id\ProductId;
use App\Models\Catalog\Id\CategoryId;

/**
 * @phpstan-template TCategory of Category
 * @phpstan-extends Model
 */
final class Product extends Model
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
     * Provides parameterized query filter traits for Eloquent models.
     */
    use Filterable;
    
    /**
     * Enables model factory integration for tests and seeding.
     */
    use HasFactory;

    /**
     * Defines the model's database table.
     *
     * @phpstan-var string
     */
    protected $table = 'products';

    /**
     * Disables auto-incrementing for primary key.
     *
     * @phpstan-var bool
     */
    public $incrementing = false;

    /**
     * Sets the primary key type to string for UUID support.
     * 
     * @phpstan-var string
     */
    protected $keyType = 'string';

    /**
     * The model attributes that are mass assignable.
     *
     * @phpstan-var list<string>
     */
    protected $fillable = [
        'name',
        'price',
        'rating',
        'in_stock',
        'category_id'
    ];

    /**
     * The attributes and their respective casts.
     * 
     * @phpstan-return array<string, class-string|non-empty-string>
     */
    protected function casts(): array
    {
        return [
            'id' => ProductId::class,
            'price' => Price::class,
            'rating' => 'float',
            'in_stock' => 'boolean',
            'category_id' => CategoryId::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime'
        ];
    }

    /**
     * Returns a new factory instance for this model.
     * 
     * @phpstan-return Factory<Product>
     */
    protected static function newFactory(): Factory
    {
        return \Database\Factories\ProductFactory::new();
    }

    /**
     * Query scope: filters products by name, using unaccented search.
     * 
     * @phpstan-param Builder $builder
     * @phpstan-param string $query
     * 
     * @phpstan-return Builder<Product>
     */
    public function scopeSearch(
        Builder $builder, string $query): Builder
    {
        $search = trim(string: $query);

        if ($search === '') {
            return $builder;
        }

        $pattern = '%' . strtolower(string: $search) . '%';

        return $builder->whereRaw(
            sql: 'public.f_unaccent("name") ILIKE ?',
            bindings: [$pattern]
        );
    }

    /**
     * Query scope: applies sorting by price, rating, or creation date.
     * 
     * @phpstan-param Builder $builder
     * @phpstan-param string $sort
     * 
     * @phpstan-return Builder<Product>
     */
    public function scopeSort(
        Builder $builder, string $sort): Builder
    {
        return match ($sort) {
            'price_asc' => $builder->orderBy(
                column: 'price'
            ),
            'price_desc' => $builder->orderByDesc(
                column: 'price'
            ),
            'rating_desc' => $builder->orderByDesc(
                column: 'rating'
            ),
            'newest' => $builder->orderByDesc(
                column: 'created_at'
            ),
            default => $builder
        };
    }

    /**
     * Defines the product-to-category relationship.
     * 
     * @phpstan-return BelongsTo<Category, Product>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(
            related: Category::class,
            foreignKey: 'category_id',
            ownerKey: 'id',
            relation: 'category'
        );
    }
}
