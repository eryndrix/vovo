<?php declare(strict_types=1);

namespace App\Shared;

use WendellAdriel\ValidatedDTO\ValidatedDTO;
use Illuminate\Http\Exceptions\HttpResponseException;
use WendellAdriel\ValidatedDTO\Concerns\EmptyCasts;
use Illuminate\Http\JsonResponse;

/**
 * @phpstan-template TCommand of Command
 */
abstract class Command extends ValidatedDTO
{
    /**
     * Enables support for empty casts.
     */
    use EmptyCasts;

    /**
     * Status code used for validation error responses.
     */
    private const int HTTP_STATUS = 422;

    /**
     * {@inheritDoc}
     */
    protected function rules(): array
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    protected function defaults(): array
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    protected function casts(): array
    {
        return [];
    }

    /**
     * Generates the HTTP response for a failed validation.
     * 
     * @throws HttpResponseException
     */
    protected function failedValidation(): void
    {
        $metadata = new Metadata();
        $error = $this->validator->errors();

        $response = new JsonResponse(data: [
            'status' => self::HTTP_STATUS,
            'result' => [
                'message' => 'Validation error.',
                'errors' => $error->messages()
            ],
            'metadata' => $metadata(),
        ], status: self::HTTP_STATUS);

        throw new HttpResponseException($response);
    }
}
