<?php declare(strict_types=1);

namespace App\Models\Catalog\Id;

use App\Shared\ValueObjects\UniqueId;
use App\Models\IdCast;

/**
 * @extends UniqueId
 */
final class ProductId extends UniqueId
{
    /**
     * Specifies the caster class used for value serialization.
     * 
     * @phpstan-return class-string<IdCast>
     */
    protected static function castClassName(): string
    {
        return IdCast::class;
    }
}
