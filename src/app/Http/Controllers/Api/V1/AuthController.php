<?php declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Route;
use App\Buses\CommandBusInterface;
use App\Domains\Identity\Login\LoginCommand;
use App\Domains\Identity\Logout\LogoutCommand;
use App\Buses\QueryBusInterface;
use App\Domains\Identity\CheckMe\CheckMeQuery;
use App\Http\Responses\DataResponse;
use App\Http\Responders\AuthResponder;
use Illuminate\Http\Request;

/**
 * @phpstan-template TCommandBus of CommandBusInterface
 * @phpstan-template TQueryBus of QueryBusInterface
 * @phpstan-extends Controller
 */
#[Prefix(prefix: 'v1')]
final class AuthController extends Controller
{
	/**
	 * Handles formatting of authentication responses.
	 * 
	 * @phpstan-var AuthResponder
	 */
	private readonly AuthResponder $responder;

	/**
	 * Injects CommandBus and QueryBus for authentication operations.
	 * 
	 * @phpstan-param TCommandBus $commandBus
	 * @phpstan-param TQueryBus $queryBus
	 */
	public function __construct(
		private CommandBusInterface $commandBus,
		private QueryBusInterface $queryBus
	) {
		$this->responder = new AuthResponder();
	}

	/**
	 * Handles user login and issues an access token.
	 * 
	 * @phpstan-param Request $request
	 * @phpstan-return DataResponse
	 */
	#[Route(methods: 'POST', uri: '/login')]
    public function login(Request $request): DataResponse
    {
    	$result = $this->commandBus->send(
    		command: LoginCommand::fromRequest(request: $request)
    	);

    	return $this->responder->login(result: $result);
    }

	/**
	 * Logs out the authenticated user and revokes their token.
	 * 
	 * @phpstan-param Request $request
	 * @phpstan-return DataResponse
	 */
	#[Route(methods: 'POST', uri: '/logout', middleware: 'auth:sanctum')]
    public function logout(Request $request): DataResponse
    {
    	$result = $this->commandBus->send(
    		command: new LogoutCommand(user: $request->user())
    	);

    	return $this->responder->logout(result: $result);
    }

	/**
	 * Retrieves information about the authenticated user.
	 * 
	 * @phpstan-param Request $request
	 * @phpstan-return DataResponse
	 */
	#[Route(methods: 'GET', uri: '/check-me', middleware: 'auth:sanctum')]
    public function checkMe(Request $request): DataResponse
    {
    	$result = $this->queryBus->ask(
    		query: new CheckMeQuery(user: $request->user())
    	);

    	return $this->responder->checkMe(result: $result);
    }
}
