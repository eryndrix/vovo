<?php declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * @extends ServiceProvider
 */
final class BusServiceProvider extends ServiceProvider
{
    /**
     * Register the application bus singletons.
     */
    public function register(): void
    {
        $this->app->singleton(
            abstract: \App\Buses\CommandBusInterface::class,
            concrete: \App\Buses\CommandBus::class
        );

        $this->app->singleton(
            abstract: \App\Buses\QueryBusInterface::class,
            concrete: \App\Buses\QueryBus::class
        );
    }
}
