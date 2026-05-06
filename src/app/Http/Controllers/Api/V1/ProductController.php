<?php declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Buses\QueryBusInterface;
use App\Domains\Product\ProductQuery;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Route;
use App\Http\Requests\ProductRequest;
use App\Http\Responders\ProductResponder;
use App\Http\Responses\DataResponse;

/**
 * @phpstan-template TEntity of ProductQuery
 * @phpstan-extends Controller
 */
#[Prefix(prefix: 'v1')]
#[Middleware(middleware: 'auth:api')]
final class ProductController extends Controller
{
	/**
	 * Handles product API responses.
	 * 
     * @phpstan-var ProductResponder
     */
	private readonly ProductResponder $responder;

	/**
	 * Provides the QueryBus dependency for product operations.
	 * 
     * @phpstan-param QueryBusInterface $queryBus
     */
	public function __construct(
		private QueryBusInterface $queryBus
	) {
		$this->responder = new ProductResponder();
	}

	/**
	 * Returns a paginated list of products using the QueryBus.
	 * 
     * @phpstan-param ProductRequest $request
     * @phpstan-return DataResponse
     */
	#[Route(methods: 'GET', uri: '/products')]
    public function index(ProductRequest $request): DataResponse
    {
    	$result = $this->queryBus->ask(
    		query: ProductQuery::fromRequest(request: $request)
    	);

    	return $this->responder->index(result: $result);
    }
}
