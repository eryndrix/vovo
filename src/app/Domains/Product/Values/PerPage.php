<?php declare(strict_types=1);

namespace App\Domains\Product\Values;

/**
 * @phpstan-consistent-constructor
 */
final readonly class PerPage
{
    /**
     * Initializes an item per page value constrained to 10–100.
     * 
     * @phpstan-param int $value
     */
    public function __construct(
    	public private(set) int $value
    ) {
        if ($this->value < 10 || $this->value > 100) {
            throw new \InvalidArgumentException(
            	message: 'PerPage must be between 10 and 100.'
            );
        }
    }
}
