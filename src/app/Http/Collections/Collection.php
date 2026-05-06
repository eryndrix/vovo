<?php declare(strict_types=1);

namespace App\Http\Collections;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Request;

/**
 * @phpstan-template TEntity
 * @extends ResourceCollection<TEntity>
 * @property \Illuminate\Pagination\LengthAwarePaginator<int, TEntity> $resource
 */
abstract class Collection extends ResourceCollection
{
    /**
     * Serializes the collection with pagination metadata and navigation links.
     * 
     * @phpstan-param Request $request
     * @phpstan-return array{
     *     0?: TEntity,
     *     meta: array{
     *         current_page: int,
     *         from: int|null,
     *         last_page: int,
     *         per_page: int,
     *         to: int|null,
     *         total: int,
     *     },
     *     links: array{
     *         first: string|null,
     *         last: string|null,
     *         prev: string|null,
     *         next: string|null,
     *     }
     * }
     */
    public function toArray(Request $request): array
    {
        $pagination = $this->resource;
        
        return [
            ...$this->collection,
            'meta' => [
                'current_page' => $pagination->currentPage(),
                'from' => $pagination->firstItem(),
                'last_page' => $pagination->lastPage(),
                'per_page' => $pagination->perPage(),
                'to' => $pagination->lastItem(),
                'total' => $pagination->total(),
            ],
            'links' => [
                'first' => $pagination->url(page: 1),
                'last' => $pagination->url(
                    page: $pagination->lastPage()
                ),
                'prev' => $pagination->previousPageUrl(),
                'next' => $pagination->nextPageUrl(),
            ],
        ];
    }
}
