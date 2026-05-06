<?php declare(strict_types=1);

namespace App\Shared\Outcomes;

use App\Shared\Result;

/**
 * @phpstan-template TError
 * @extends Result<never, TError>
 */
final class Failure extends Result
{
    /**
     * Creates a new Failure instance.
     * 
     * @phpstan-param TError $error
     */
    public function __construct(mixed $error)
    {
        parent::__construct(error: $error);
    }

    /**
     * Checks if this is a success result.
     * 
     * @phpstan-return false
     */
    public function isSuccess(): bool {
        return false;
    }
}
