<?php declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domains\Identity\UserRepositoryInterface;
use App\Domains\Identity\UserRepository;
use App\Domains\Product\Repositories\ProductRepositoryInterface;
use App\Domains\Product\Repositories\ProductRepository;

/**
 * @phpstan-template TRepository of object
 */
final class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register application repository interface bindings.
     */
    public function register(): void
    {
        $this->app->bind(
            abstract: UserRepositoryInterface::class,
            concrete: UserRepository::class
        );

        $this->app->bind(
            abstract: ProductRepositoryInterface::class,
            concrete: ProductRepository::class
        );
    }
}
