<?php

namespace Foundation\Router;

use App\Common\Patterns\Singleton;
use App\Foundation\HTTP\Enums\HTTPMethodsEnum;
use App\Foundation\HTTP\Exceptions\NotFoundException;
use App\Foundation\HTTP\Middlewares\MiddlewareContract;
use App\Foundation\HTTP\Request;
use App\Foundation\HTTP\Response;
use App\Http\Common\BaseController;
use App\Models\Common\BaseModel;
use JetBrains\PhpStorm\ArrayShape;
use ReflectionClass;
use ReflectionException;

/**
 * @method static Router getInstance();
 */
class Router extends Singleton
{
    protected array $compiled_routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => [],
    ];

    public function compileRoutes(): void
    {
        $route_scheme = require_once path('routes/api.php');
    }

    public function getCompiledRoutes(): array
    {
        return $this->compiled_routes;
    }

    public static function get(string $url, string $action): Route
    {
        $self = self::getInstance();
        $route = $self->setRouteToCompile(HTTPMethodsEnum::Get, $url, $action);

        return $route;
    }

    public static function post(string $url, string $action): Route
    {
        $self = self::getInstance();
        $route = $self->setRouteToCompile(HTTPMethodsEnum::Post, $url, $action);

        return $route;
    }

    public static function put(string $url, string $action): Route
    {
        $self = self::getInstance();
        $route = $self->setRouteToCompile(HTTPMethodsEnum::Put, $url, $action);

        return $route;
    }

    public static function delete(string $url, string $action): Route
    {
        $self = self::getInstance();
        $route = $self->setRouteToCompile(HTTPMethodsEnum::Delete, $url, $action);

        return $route;
    }

    protected function setRouteToCompile(HTTPMethodsEnum $method, string $url, string $action): Route
    {
        $action_exploded = explode('@', $action);
        if (count($action_exploded) == 1) {
            $complete_action = [
                'class' => $action_exploded[0],
                'method' => 'handle',
            ];
        } else {
            $complete_action = [
                'class' => $action_exploded[0],
                'method' => $action_exploded[1],
            ];
        }

        $regex = self::getRegexFromURL($url);

        $route = new Route(
            method: $method,
            path: $url,
            pattern: $regex['pattern'],
            variables: $regex['variables'],
            controller_class: $complete_action['class'],
            controller_method: $complete_action['method'],
        );

        $this->compiled_routes[$method->value][] = &$route;

        return $route;
    }

    #[ArrayShape(['pattern' => "string", 'variables' => "array"])]
    protected static function getRegexFromURL(string $url): array
    {
        $exploded_url = explode('/', $url);
        $result_url = [];
        $url_variables = [];
        foreach ($exploded_url as $url_token) {
            if (str_starts_with($url_token, '{') && str_ends_with($url_token, '}')) {
                $result_url[] = '(.*)';
                $url_variables[] = substr($url_token, 1, strlen($url_token) - 2);
            } elseif ($url_token == '') {
                $result_url[] = '.*';
            } else {
                $result_url[] = $url_token;
            }
        }

        return [
            'pattern' => '/^' . implode('\/', $result_url) . '$/',
            'variables' => $url_variables
        ];
    }


    /**
     * @throws ReflectionException
     * @throws NotFoundException
     */
    public function execute(Request $request): Response
    {
        /** @var Route $route */
        foreach ($this->compiled_routes[$request->getMethod()->value] as $route) {
            if (preg_match($route->pattern, $request->getPath(), $router_results)) {
                $route_variables = [];
                for ($i = 1; $i <= count($route->variables); $i++) {
                    if (isset($router_results[$i])) {
                        $route_variables[$route->variables[$i - 1]] = $router_results[$i];
                    }
                }

                $request->setRouterVariables($route_variables);

                $class = $route->controller_class;
                $method = $route->controller_method;

                $executable_method_params = [];
                $controller_reflection = new ReflectionClass($class);

                foreach ($controller_reflection->getMethod($method)->getParameters() as $method_param) {
                    // Если не указывать тип данных в сигнатуре функции контроллера, вернётся null
                    if (is_null($method_param->getType())) {
                        if (array_key_exists($method_param->getName(), $route_variables)) {
                            $executable_method_params[$method_param->getName()] = $route_variables[$method_param->getName()];
                        }
                    } else {
                        $method_param_class = $method_param->getType()->getName();
                        $scalar_types = [
                            'bool',
                            'int',
                            'float',
                            'string',
                            'array',
                            'object',
                            'callable',
                            'mixed',
                            'resource'
                        ];

                        if (!in_array($method_param_class, $scalar_types) && is_a($method_param_class, BaseModel::class, true) && array_key_exists($method_param->getName(), $route_variables)) {
                            $executable_method_params[$method_param->getName()] = $method_param_class::findOrFail($route_variables[$method_param->getName()]);
                        } elseif ($method_param_class == Request::class) {
                            $executable_method_params[$method_param->getName()] = $request;
                        } elseif (array_key_exists($method_param->getName(), $route_variables)) {
                            $executable_method_params[$method_param->getName()] = $route_variables[$method_param->getName()];
                        }
                    }
                }

                $middlewares = $this->chainFormationMiddleware(config('http.middlewares.global'));

                $this->handleChainMiddleware($request, $middlewares);

                if ($route->hasMiddlewares()) {
                    $middlewares = $this->chainFormationMiddleware($route->getMiddlewares());

                    $this->handleChainMiddleware($request,$middlewares);
                }
                /**
                 * @var BaseController $controller
                 */
                $controller = new $class();

                if ($controller->hasMiddleware($method)) {
                    $middlewares = $this->chainFormationMiddleware($controller->getMiddlewares($method));

                    $this->handleChainMiddleware($request,$middlewares);
                }

                return $controller->$method(...$executable_method_params);
            }
        }

        throw new NotFoundException();
    }


    public function chainFormationMiddleware(array $middleware_classes): array
    {
        /** @var MiddlewareContract[] $middlewares */
        $middlewares = [];

        if (count($middleware_classes) == 1) {
            /** @var MiddlewareContract $current_middleware */
            $current_middleware = new $middleware_classes[0]();
            $middlewares[] = $current_middleware;


        } else {
            for ($i = count($middleware_classes) - 1; $i >= 0; $i--) {
                if ($i == count($middleware_classes) - 1) {
                    continue;
                }
                if (!count($middlewares)) {
                    /** @var MiddlewareContract $current_middleware */
                    $current_middleware = new $middleware_classes[$i]();

                    /** @var MiddlewareContract $current_middleware */
                    $next_middleware = new $middleware_classes[$i + 1]();
                    $current_middleware->next($next_middleware);
                    $middlewares[] = $current_middleware;
                } else {
                    /** @var MiddlewareContract $current_middleware */
                    $current_middleware = new $middleware_classes[$i]();
                    $current_middleware->next($middlewares[count($middlewares) - 1]);

                    $middlewares[] = $current_middleware;
                }

            }
        }
        return $middlewares;
    }

    /**
     * @param $request
     * @param MiddlewareContract[] $middlewares
     * @return void
     */
    public function handleChainMiddleware($request, $middlewares): void
    {
        if (count($middlewares)) {
            $root = $middlewares[count($middlewares) - 1];
            $root->handle($request);
        }
    }
}
