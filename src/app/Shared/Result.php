<?php declare(strict_types=1);

namespace App\Shared;

use App\Shared\Outcomes\Success;
use App\Shared\Outcomes\MapHelpers;
use App\Shared\Outcomes\Failure;

/**
 * @phpstan-template TSuccess
 * @phpstan-template TError
 */
abstract class Result
{
    /**
     * Adds Result transformation utilities.
     */
    use MapHelpers;

    /**
     * Initializes Result with value or error.
     * 
     * @phpstan-param TSuccess|null $value
     * @phpstan-param TError|null $error
     */
    protected function __construct(
        public private(set) mixed $value = null,
        public private(set) mixed $error = null
    ) {}

    /**
     * Factory method to create a new Success result.
     * 
     * @phpstan-template TNewSuccess
     * @phpstan-param TNewSuccess $value
     * 
     * @phpstan-return Success<TNewSuccess>
     */
    public static function success(
        mixed $value): Success
    {
        return new Success(value: $value);
    }

    /**
     * Factory method to create a new Failure result.
     * 
     * @phpstan-template TNewError
     * @phpstan-param TNewError $error
     * 
     * @phpstan-return Failure<TNewError>
     */
    public static function failure(
        mixed $error): Failure
    {
        return new Failure(error: $error);
    }

    /**
     * Checks if the result is successful.
     * 
     * @phpstan-return bool
     */
    abstract public function isSuccess(): bool;

    /**
     * Checks if the result is a failure.
     * 
     * @phpstan-return bool
     */
    public function isFailure(): bool
    {
        return !$this->isSuccess();
    }
}
