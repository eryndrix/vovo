<?php declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domains\Identity\CheckMe\CheckMeQuery;
use App\Domains\Identity\CheckMe\CheckMeHandler;
use App\Domains\Product\ProductQuery;
use App\Domains\Product\ProductHandler;
use App\Buses\QueryBusInterface;

/**
 * @extends ServiceProvider
 */
final class QueryServiceProvider extends ServiceProvider
{
    /**
     * Registers query handlers to the QueryBus.
     *
     * @phpstan-param QueryBusInterface $queryBus
     */
    public function boot(QueryBusInterface $queryBus): void
    {
        $queryBus->register(map: [
            CheckMeQuery::class => CheckMeHandler::class,
            ProductQuery::class => ProductHandler::class,
        ]);
    }
}
