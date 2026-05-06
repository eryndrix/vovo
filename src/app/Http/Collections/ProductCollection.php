<?php declare(strict_types=1);

namespace App\Http\Collections;

use App\Http\Resources\ProductResource;

/**
 * @phpstan-template TEntity of ProductResource
 * @extends Collection<TEntity>
 */
final class ProductCollection extends Collection
{
    /**
     * Specifies the resource class used to transform each item.
     * 
     * @phpstan-var class-string<ProductResource>
     */
    public $collects = ProductResource::class;
}
