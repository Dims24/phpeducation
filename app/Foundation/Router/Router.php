<?php

namespace Foundation\Router;

use App\Foundation\HTTP\Enums\HTTPMethodsEnum;
use App\Foundation\HTTP\Exceptions\NotFoundException;
use App\Foundation\HTTP\Request;
use App\Foundation\HTTP\Response;
use App\Helpers\FilesystemHelper;
use ReflectionClass;

class Router
{
    protected static array $compiled_routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => [],
    ];

    public function __construct()
    {
    }

    public function compileRoutes(): void
    {
        #Возвращает 1
        $route_scheme = require_once FilesystemHelper::getPath('routes/api.php');
    }

    public function getCompiledRoutes(): array
    {
        return self::$compiled_routes;
    }

    public static function get(string $url, string $action): void
    {
        self::setRouteToCompile(HTTPMethodsEnum::Get, $url, $action);
    }

    public static function post(string $url, string $action): void
    {
        self::setRouteToCompile(HTTPMethodsEnum::Post, $url, $action);
    }

    public static function put(string $url, string $action): void
    {
        self::setRouteToCompile(HTTPMethodsEnum::Put, $url, $action);
    }

    public static function delete(string $url, string $action): void
    {
        self::setRouteToCompile(HTTPMethodsEnum::Delete, $url, $action);
    }

    protected static function setRouteToCompile(HTTPMethodsEnum $method, string $url, string $action): void
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

        self::$compiled_routes[$method->value][] = [
            'url' => self::getRegexFromURL($url),
            'controller' => $complete_action,
        ];
    }

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
     * @throws \ReflectionException
     * @throws NotFoundException
     */
    public function execute(Request $request): Response
    {
        foreach (self::$compiled_routes[$request->getMethod()->value] as $action) {
            if (preg_match($action["url"]['pattern'], $request->getPath(), $router_results)) {
                $result = [];
                for ($i = 1; $i <= count($action['url']['variables']); $i++) {
                    if (isset($router_results[$i])) {
                        $result[$action['url']['variables'][$i - 1]] = $router_results[$i];
                    }
                }

                $class = $action['controller']['class'];
                $method = $action['controller']['method'];

                $executable_method_params = [];
                $controller_reflection = new ReflectionClass($class);

                foreach ($controller_reflection->getMethod($method)->getParameters() as $method_param) {
                    if ($method_param->getType()) {
                        if ($method_param->getType()->getName() == Request::class) {
                            $executable_method_params[$method_param->getName()] = $request;
                        } else {
                            # TODO: Здесь могут быть переменные из маршрута
                            $executable_method_params[$method_param->getName()] = null;
                        }
                    }
                }
                $executable_method_params = array_merge($executable_method_params, $result);
                $controller = new $class();
                return $controller->$method(...$executable_method_params);
            }
        }

        throw new NotFoundException();
    }
}
