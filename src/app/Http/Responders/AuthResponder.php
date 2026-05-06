<?php declare(strict_types=1);

namespace App\Http\Responders;

use App\Models\Account\User;
use Illuminate\Http\Response as Status;
use App\Http\Resources\UserResource;
use App\Domains\Identity\Login\LogoutError;
use App\Domains\Identity\Login\LoginError;
use App\Domains\Identity\Login\CheckMeError;
use App\Http\Responses\DataResponse;
use App\Shared\Result;

/**
 * @phpstan-consistent-constructor
 */
final class AuthResponder
{
    /**
     * Returns a login response based on the provided Result.
     * 
     * @phpstan-param Result $result
     * @phpstan-return DataResponse
     */
    public function login(Result $result): DataResponse
    {
        return $result->match(
            onSuccess: fn (string $token) => new DataResponse(
                data: [
                    'message' => __(key: 'auth.login_successful'),
                    'token' => $token,
                ],
                status: Status::HTTP_OK
            ),
            onError: fn (LoginError $error) => new DataResponse(
                data: ['message' => __(key: $error->message())],
                status: $error->status()
            )
        );
    }

    /**
     * Returns a logout response based on the provided Result.
     * 
     * @phpstan-param Result $result
     * @phpstan-return DataResponse
     */
    public function logout(Result $result): DataResponse
    {
        return $result->match(
            onSuccess: fn (bool $result) => new DataResponse(
                data: [
                    'message' => __(key: 'auth.logout_successful')
                ],
                status: Status::HTTP_OK
            ),
            onError: fn (LogoutError $error) => new DataResponse(
                data: ['message' => __(key: $error->message())],
                status: $error->status()
            )
        );
    }

    /**
     * Returns a user status response for the current authenticated user ("me") based on the provided Result.
     * 
     * @phpstan-param Result $result
     * @phpstan-return DataResponse
     */
    public function checkMe(Result $result): DataResponse
    {
        return $result->match(
            onSuccess: fn (User $user) => new DataResponse(
                data: new UserResource(resource: $user),
                status: Status::HTTP_OK
            ),
            onError: fn (CheckMeError $error) => new DataResponse(
                data: ['message' => __(key: $error->message())],
                status: $error->status()
            )
        );
    }
}
