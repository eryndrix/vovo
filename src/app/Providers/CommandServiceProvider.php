<?php declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domains\Identity\Login\LoginCommand;
use App\Domains\Identity\Login\LoginProcess;
use App\Domains\Identity\Logout\LogoutCommand;
use App\Domains\Identity\Logout\LogoutHandler;
use App\Buses\CommandBusInterface;

/**
 * @extends ServiceProvider
 */
final class CommandServiceProvider extends ServiceProvider
{
    /**
     * Register the command handlers with the command bus.
     *
     * @phpstan-param CommandBusInterface $commandBus
     */
    public function boot(CommandBusInterface $commandBus): void
    {
        $commandBus->register(map: [
            LoginCommand::class => LoginProcess::class,
            LogoutCommand::class => LogoutHandler::class
        ]);
    }
}
