<?php declare(strict_types=1);

namespace App\Shared\Outcomes;

/**
 * @phpstan-template TSuccess
 * @phpstan-template TError
 */
trait MapHelpers
{
	/**
     * Maps the value to a new one on success.
     * 
     * @template TNewSuccess
     * @phpstan-param callable(TSuccess|null): TNewSuccess $mapper
     * @phpstan-return Result<TNewSuccess, TError>
     */
    public function map(callable $mapper): self
    {
        if (!$this->isSuccess() || $this->value === null) {
            return $this;
        }

        return self::success(value: $mapper($this->value));
    }

    /**
     * Maps the error to a new one on failure.
     * 
     * @template TNewError
     * @phpstan-param callable(TError|null): TNewError $mapper
     * @phpstan-return Result<TSuccess, TNewError>
     */
    public function mapError(callable $mapper): self
    {
        if ($this->isFailure() && $this->error !== null) {
            return self::failure(error: $mapper($this->error));
        }

        return $this;
    }

    /**
     * Matches success or failure handlers.
     * 
     * @phpstan-param callable(TSuccess|null): mixed $onSuccess
     * @phpstan-param callable(TError|null): mixed $onError
     * 
     * @phpstan-return mixed
     */
    public function match(
        callable $onSuccess, callable $onError): mixed
    {
        return $this->isSuccess()
            ? $onSuccess($this->value)
            : $onError($this->error);
    }
}
