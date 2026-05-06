<?php declare(strict_types=1);

namespace App\Domains\Identity\Login;

use App\Shared\Process;
use App\Domains\Identity\Login\Handlers\RateLimitHandler;
use App\Domains\Identity\Login\Handlers\LoadUserByEmailHandler;
use App\Domains\Identity\Login\Handlers\CheckPasswordHandler;
use App\Domains\Identity\Login\Handlers\UpdateRememberTokenHandler;
use App\Domains\Identity\Login\Handlers\CreateTokenHandler;
use Illuminate\Support\Facades\Log;
use App\Shared\Result;

/**
 * @phpstan-consistent-constructor
 */
final class LoginProcess extends Process
{
    /**
     * Handler pipeline executed in order during login.
     * 
     * @phpstan-var list<class-string>
     */
    protected array $handlers = [
        RateLimitHandler::class,
        LoadUserByEmailHandler::class,
        CheckPasswordHandler::class,
        UpdateRememberTokenHandler::class,
        CreateTokenHandler::class,
    ];

    /**
     * Executes the login process for the provided command.
     * 
     * @phpstan-param LoginCommand $command
     * @phpstan-return Result
     */
    public function __invoke(LoginCommand $command): Result
    {
        return $this->run(
            context: LoginContext::of(command: $command)
        )->mapError(
            mapper: function (LoginError $error) use ($command) {
                $level = match ($error) {
                    LoginError::TokenCreationFailed => 'error',
                    LoginError::TooManyLoginAttempts => 'info',
                    default => 'warning',
                };

                Log::$level(
                    message: 'Login failed',
                    context: [
                        'error' => $error->value,
                        'status' => $error->status(),
                        'email' => $command->email
                    ]
                );

                return $error;
            }
        );
    }
}
