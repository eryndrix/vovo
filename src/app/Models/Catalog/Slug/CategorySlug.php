<?php declare(strict_types=1);

namespace App\Models\Catalog\Slug;

use App\Shared\ValueObjects\UniqueSlug;
use App\Models\SlugCast;

/**
 * @phpstan-extends UniqueSlug
 */
final class CategorySlug extends UniqueSlug
{
    /**
     * Construct a category slug, enforcing a length of 2 to 50 characters.
     * 
     * @phpstan-param string $slug
     */
    protected function __construct(string $slug)
    {
        parent::__construct(slug: $slug);

        if (strlen(string: $slug) < 2
            || strlen(string: $slug) > 50
        ) {
            $message = 'Category slug length 2-50 chars.';
        
            throw new \InvalidArgumentException(
                message: $message
            );
        }
    }

    /**
     * Specifies the value caster used for serialization.
     * 
     * @phpstan-return class-string<SlugCast>
     */
    protected static function castClassName(): string
    {
        return SlugCast::class;
    }
}
