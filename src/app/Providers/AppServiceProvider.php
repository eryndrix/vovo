<?php declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Cache\RateLimiting\Limit;
use App\Models\Account\Token;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\Sanctum;

/**
 * @extends ServiceProvider
 */
final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local') && class_exists(
            class: \Laravel\Telescope\TelescopeServiceProvider::class)
        ) {
            $this->app->register(
                provider: \Laravel\Telescope\TelescopeServiceProvider::class
            );
            
            $this->app->register(
                provider: TelescopeServiceProvider::class
            );
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::shouldBeStrict(shouldBeStrict: true);
        Model::preventLazyLoading(value: !$this->app->isProduction());

        if ($this->app->environment('production')) {
            URL::forceScheme(scheme: 'https');
        }

        RateLimiter::for(name: 'api',
            callback: function (Request $request): mixed {
                return Limit::perMinute(maxAttempts: 60)->by(
                    key: $request->user()?->id ?? $request->ip()
                );
            }
        );

        Sanctum::usePersonalAccessTokenModel(model: Token::class);
    }
}
