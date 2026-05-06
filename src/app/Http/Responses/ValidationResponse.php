<?php declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\MessageBag;
use App\Shared\Metadata;

final class ValidationResponse implements Responsable
{
    /**
     * HTTP status code used for validation failures.
     */
    private const int HTTP_STATUS = 422;

    /**
     * Initialize response with the provided validation errors.
     * 
     * @phpstan-param MessageBag $errors
     */
    public function __construct(
        private readonly MessageBag $errors
    ) {}

    /**
     * Generate a JSON response for validation errors.
     * 
     * @phpstan-param mixed $request
     * @phpstan-return JsonResponse
     */
    public function toResponse($request): JsonResponse
    {
        $metadata = new Metadata();

        $data = [
            'status' => self::HTTP_STATUS,
            'result' => [
                'message' => 'Validation error.',
                'errors' => $this->errors
            ],
            'metadata' => $metadata()
        ];

        return new JsonResponse(
            data: $data, status: self::HTTP_STATUS);
    }
}
