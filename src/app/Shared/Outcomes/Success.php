<?php declare(strict_types=1);

namespace App\Shared\Outcomes;

use App\Shared\Result;

/**
 * @phpstan-template TSuccess
 * @extends Result<TSuccess, never>
 */
final class Success extends Result
{
    /**
     * Creates a new Success instance.
     * 
     * @phpstan-param TSuccess $value
     */
    public function __construct(mixed $value)
    {
        parent::__construct(value: $value);
    }

    /**
     * Checks if this is a success result.
     * 
     * @phpstan-return true
     */
    public function isSuccess(): bool {
        return true;
    }
}
