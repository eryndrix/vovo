<?php declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Http\Response as Status;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use App\Shared\Metadata;

final class DataResponse implements Responsable
{
    /**
     * Constructs a successful response with payload and HTTP status.
     * 
     * @phpstan-param mixed $data
     * @phpstan-param int $status
     */
    public function __construct(
        private mixed $data,
        private int $status = Status::HTTP_OK
    ) {}

    /**
     * Generates a JSON response containing data and metadata.
     * 
     * @phpstan-param mixed $request
     * @phpstan-return JsonResponse
     */
    public function toResponse($request): JsonResponse
    {
        $metadata = new Metadata();

        $data = [
            'status' => $this->status,
            'data' => $this->data,
            'metadata' => $metadata(),
        ];

        return new JsonResponse(
            data: $data, status: $this->status);
    }
}
