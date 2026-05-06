<?php declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

/**
 * @extends JsonResource<object>
 */
final class ProductResource extends JsonResource
{
    /**
     * Maps Product model data to a structured API response.
     * 
     * @phpstan-param Request $request
     * @phpstan-return array{
     *     id: string,
     *     name: string,
     *     price: string,
     *     rating: int|float|null,
     *     in_stock: bool,
     *     category: array{id: string, name: string, slug: string}|null,
     *     datetime: array{created_at: string|null, updated_at: string|null}
     * }
     */
    public function toArray(Request $request): array
    {
        $product = $this->resource;

        return [
            'id' => $product->id->asString(),
            'name' => $product->name,
            'price' => $product->price->formatted(),
            'rating' => $product->rating,
            'in_stock' => $product->inStock,
            'category' => $this->whenLoaded(
                relationship: 'category',
                value: function () use($product) {
                    $category = $product->category;

                    return [
                        'id' => $category->id->asString(),
                        'name' => $category->name,
                        'slug' => $category->slug->asString(),
                    ];
                }
            ),
            'datetime' => [
                'created_at' => $product->createdAt?->format(
                    format: 'Y-m-d H:i:s'
                ),
                'updated_at' => $product->updatedAt?->format(
                    format: 'Y-m-d H:i:s'
                ),
            ],
        ];
    }
}
