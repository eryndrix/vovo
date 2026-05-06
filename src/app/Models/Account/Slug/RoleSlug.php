<?php declare(strict_types=1);

namespace App\Models\Account\Slug;

use App\Shared\ValueObjects\UniqueSlug;
use App\Models\SlugCast;

/**
 * @extends UniqueSlug
 */
final class RoleSlug extends UniqueSlug
{
    /**
     * Construct a role slug and enforce 3-20 character length.
     * 
     * @phpstan-param string $slug
     */
    protected function __construct(string $slug)
    {
        parent::__construct(slug: $slug);

        if (strlen(string: $slug) < 3
            || strlen(string: $slug) > 20
        ) {
            throw new \InvalidArgumentException(
                message: 'Role slug length 2-50 chars.'
            );
        }
    }
    
    /**
     * Get the slug caster class for serialization.
     * 
     * @phpstan-return class-string<SlugCast>
     */
    protected static function castClassName(): string
    {
        return SlugCast::class;
    }
}
