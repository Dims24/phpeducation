<?php

namespace Router;

use Application;
use Helpers\FilesystemHelper;
use MyProject\Models\Articles\Article;

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
        $route_scheme = require_once FilesystemHelper::getPath('routes/api.php');
    }

    public function getCompiledRoutes(): array
    {
        return self::$compiled_routes;
    }

    public static function get(string $url, string $action): void
    {
        self::setRouteToCompile(RouterHTTPMethodsEnum::Get, $url, $action);
    }

    public static function post(string $url, string $action): void
    {
        self::setRouteToCompile(RouterHTTPMethodsEnum::Post, $url, $action);
    }

    public static function put(string $url, string $action): void
    {
        self::setRouteToCompile(RouterHTTPMethodsEnum::Put, $url, $action);
    }

    public static function delete(string $url, string $action): void
    {
        self::setRouteToCompile(RouterHTTPMethodsEnum::Delete, $url, $action);
    }

    protected static function setRouteToCompile(RouterHTTPMethodsEnum $method, string $url, string $action): void
    {
        self::$compiled_routes[$method->value][] = [
            'url' => $url,
            'action' => $action,
        ];
    }

    public function controllermethod($action): mixed
    {

        $class = stristr($action, '@', true);
        $method = substr(stristr($action, '@'), 1);
        $test2 = new $class();
        $test2->$method();
        return $test2;
    }


}