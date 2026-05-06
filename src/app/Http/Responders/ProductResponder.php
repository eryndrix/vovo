<?php declare(strict_types=1);

namespace App\Http\Responders;

use Illuminate\Http\Response as Status;
use App\Http\Collections\ProductCollection;
use App\Domains\Product\ProductError;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Responses\DataResponse;
use App\Shared\Result;

/**
 * @phpstan-type TError ProductError
 * @phpstan-type TSuccess LengthAwarePaginator
 */
final class ProductResponder
{
    /**
     * Generates an index response based on the provided Result instance.
     * 
     * @phpstan-param Result $result
     * @phpstan-return DataResponse
     */
    public function index(Result $result): DataResponse
    {
        return $result->match(
            onSuccess: fn (LengthAwarePaginator $paginator) => new DataResponse(
                data: new ProductCollection(resource: $paginator),
                status: Status::HTTP_OK
            ),
            onError: fn (ProductError $error) => new DataResponse(
                data: ['message' => __(key: $error->message())],
                status: $error->status()
            )
        );
    }
}
