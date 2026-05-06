<?php declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @phpstan-template TValidated of array{
 *     q?: string|null,
 *     price_from?: float|null,
 *     price_to?: float|null,
 *     category_id?: string|null,
 *     in_stock?: bool,
 *     rating_from?: float|null,
 *     sort?: string|null,
 *     per_page?: int
 * }
 * 
 * @extends FormRequest<TValidated>
 */
final class ProductRequest extends FormRequest
{
    /**
     * Default value for products per page if omitted in the request.
     *
     * @phpstan-var int
     */
    private const int DEFAULT_PER_PAGE = 15;

    /**
     * Authorize only GET requests.
     *
     * @phpstan-return bool
     */
    public function authorize(): bool
    {
        return $this->getMethod() === 'GET';
    }

    /**
     * Returns validation rules for product filters.
     *
     * @phpstan-return array<string, list<mixed>>
     */
    public function rules(): array
    {
        $common = ['bail', 'sometimes', 'nullable'];

        return [
            'q' => [
                ...$common, 'string', 'max:100'
            ],
            'price_from' => [
                ...$common, 'numeric', 'min:10', 'max:10000', 'lt:price_to'
            ],
            'price_to' => [
                ...$common, 'numeric', 'min:10', 'max:10000', 'gt:price_from'
            ],
            'category_id' => [
                ...$common, 'uuid', 'exists:categories,id'
            ],
            'in_stock' => [
                ...$common, 'boolean'
            ],
            'rating_from' => [
                ...$common, 'numeric', 'min:0', 'max:5'
            ],
            'sort' => [
                ...$common, 'string', Rule::in(values: [
                    'price_asc', 'price_desc', 'rating_desc', 'newest'
                ]),
            ],
            'per_page' => [
                'bail', 'sometimes', 'integer', 'min:10', 'max:100'
            ],
        ];
    }

    /**
     * Returns validated and normalized filter data for product queries.
     *
     * @phpstan-return array{
     *   q: string|null,
     *   price_from: float|null,
     *   price_to: float|null,
     *   category_id: string|null,
     *   in_stock: bool,
     *   rating_from: float|null,
     *   sort: string|null,
     *   per_page: int
     * }
     */
    public function validatedData(): array
    {
        return [
            'q' => $this->normalizeString(
                input: 'q'
            ),
            'price_from' => $this->getFloatOrNull(
                key: 'price_from'
            ),
            'price_to' => $this->getFloatOrNull(
                key: 'price_to'
            ),
            'category_id' => $this->normalizeString(
                input: 'category_id'
            ),
            'in_stock' => $this->boolean(
                key: 'in_stock'
            ),
            'rating_from' => $this->getFloatOrNull(
                key: 'rating_from'
            ),
            'sort' => $this->normalizeString(
                input: 'sort'
            ),
            'per_page' => $this->integer(
                key: 'per_page',
                default: self::DEFAULT_PER_PAGE
            ),
        ];
    }

    /**
     * Trims input and converts empty strings to null.
     *
     * @phpstan-param string|null $input
     * @phpstan-return string|null
     */
    private function normalizeString(?string $input): ?string
    {
        if ($input === null) {
            return null;
        }

        $normalized = $this->string(key: $input)
            ->trim()
            ->toString();

        return $normalized === '' ? null : $normalized;
    }

    /**
     * Returns float value for the request key or null if missing.
     *
     * @phpstan-param string $key
     * @phpstan-return float|null
     */
    private function getFloatOrNull(string $key): ?float
    {
        $value = $this->float(key: $key);

        if ($this->missing(key: $key)) {
            return null;
        }

        return $value;
    }
}
