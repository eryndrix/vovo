<?php declare(strict_types=1);

namespace App\Shared;

use Illuminate\Support\Facades\Context;

/**
 * @phpstan-type TMetadata array{request_id: mixed, timestamp: mixed}
 */
final readonly class Metadata
{
    /**
     * Retrieves request metadata as an array.
     * 
     * @phpstan-return TMetadata
     */
    public function __invoke(): array
    {
        return [
            'request_id' => Context::get(key: 'request_id'),
            'timestamp' => Context::get(key: 'timestamp')
        ];
    }
}
