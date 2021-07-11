==== This is not a FIG PSR and has not undergone the rigorous process to become one ====

# PSR 101

This is a rewrite of PSR 15, and an inclusion of PSR 17 into the AppInterface.  Various ideas went into this:

-	Because the inteface method parameters must match (see PSR 100 README), and because PSR 101 middleware required the handler to be the extended version of the RequestionHandlerInterface, PSR 15 could not be extended for the middleware interfaces
-	To write standardized middleware, the method of using PSR 17 factories should be standardized
	-	To do this, the AppInterface provides access to those factories
-	Some middleware does not care about the App, it only wants a closure for a `next` call.  This type of middleware is separately provided for as `MiddlewareNextInterface`
	-	Apps should handle this sort of middleware by detecting which type of middleware and applying correctly
-	Middleware is expected with AppInterface.  As such there are methods for adding, removing and checking middleware.
	-	These methods are the uncontexted `has`, `add`, `remove`.  The expectation here is that frameworks will generally not have these methods on their $app object (b/c they are too general), but that, it would make sense for these methods to refer to middleware.  Further, to add something like `addMiddleware` to the interface potentially conflicts with existing methods on $app objects of frameworks.



## Use
Let's see a middleware based on this PSR 101

```php
use Psg\Psr100\Factory\Psr100Factory;
use Psg\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psg\Http\Server\{MiddlewareAppInterface, MiddlewareInterface, MiddlewareNextInterface};


class App extends Psr100Factory implements MiddlewareAppInterface{
	public $middleware;
	public function __construct(){
		$this->middleware = new \SplObjectStorage();
	}
	public function handle(\Psr\Http\Message\ServerRequestInterface $request): ResponseInterface {
		$middleware = $this->middleware->current();
		$this->middleware->next();
		return $middleware->process($request, $this);
	}
	public function add($middleware){
		$this->middleware->attach($middleware);
	}
	public function remove($middleware){
		$this->middleware->detach($middleware);
	}
	public function has($middleware){
		return $this->middleware->contains($middleware);
	}
}

class Control implements MiddlewareInterface {
	public function process(ServerRequestInterface $request, AppInterface $app): ResponseInterface {
		return $app->createResponse(200)->withBody($app->createStream('there is one name in programming, and that name is bill'));
	}
}
class MiddleOut implements MiddlewareInterface {
	public function process(ServerRequestInterface $request, AppInterface $app): ResponseInterface {
		$response = $app->handle($request);
		$only_bob = preg_replace('/bill/i', 'bob', (string)$response->getbody());
		$new_stream = $response->getBody()->create($only_bob);
		return $response->withBody($new_stream);
	}
}

$App = new App;
$App->add(new MiddleOut);
$App->add(new Control);
$App->middleware->rewind();
$response = $App->handle($App->createServerRequest('get', 'http://bobery.com/'));
echo (string)$response->getBody();

#> there is one name in programming, and that name is bob
```