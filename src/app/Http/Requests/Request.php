<?php declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Responses\ValidationResponse;
use Illuminate\Contracts\Validation\Validator;

/**
 * @phpstan-consistent-constructor
 * @extends FormRequest
 */
abstract class Request extends FormRequest
{
    /**
     * Determines if the user is authorized to make this request.
     *
     * @phpstan-return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Returns the validation rules for this request.
     *
     * @phpstan-return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    abstract public function rules(): array;

    /**
     * Handles a failed validation attempt by returning a validation error response.
     *
     * @phpstan-param Validator $validator
     */
    protected function failedValidation(Validator $validator): void
    {
        $response = new ValidationResponse(
            errors: $validator->errors()
        );

        throw new HttpResponseException(
            response: $response->toResponse(request: $this)
        );
    }
}
