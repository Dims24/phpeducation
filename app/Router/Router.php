<?php

namespace Router;

use Foundation\HTTP\HTTPMethodsEnum;
use Foundation\HTTP\Request;
use Helpers\FilesystemHelper;

class Router
{
    protected static array $compiled_routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => [],
    ];

    public function __construct()
    {}

    public function compileRoutes(): void
    {
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
            'url' => $url,
            'controller' => $complete_action,
        ];
    }

    public function execute(Request $request): mixed
    {
        foreach (self::$compiled_routes[$request->getMethod()->value] as $action) {
            if (str_starts_with($request->getUri(), $action['url'])) {
                $class = $action['controller']['class'];
                $method = $action['controller']['method'];

                $executable_method_params = [];
                $controller_reflection = new \ReflectionClass($class);
                foreach ($controller_reflection->getMethod($method)->getParameters() as $method_param) {
                    if ($method_param->getType()->getName() == Request::class) {
                        $executable_method_params[$method_param->getName()] = $request;
                    } else {
                        # TODO: Здесь могут быть переменные из маршрута
                        $executable_method_params[$method_param->getName()] = null;
                    }
                }

                $controller = new $class();
                return $controller->$method(...$executable_method_params);
            }
        }

        # TODO: Добавить возврат 404
        return null;
    }
}