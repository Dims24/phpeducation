<?php

namespace App\Foundation;

use App\Common\Patterns\Singleton;
use App\Foundation\Config\Config;
use App\Foundation\Exception\ExceptionHandler;
use App\Foundation\HTTP\Request;
use App\Helpers\Env\Env;
use Foundation\Router\Router;

class Application extends Singleton
{
    protected Router $router;
    protected string $root_path;

    #Функция запуска приложения
    public function run()
    {
        $this->init();

        try {
            $this->initRouter();

            $response = $this->router->execute(
                $this->captureRequest()
            );

            $response->send();

            exit(0);
        } catch (\Throwable $exception) {
            ExceptionHandler::handleException($exception);
        }
    }

    /**
     * @return string
     */
    public function getRootPath(): string
    {
        return $this->root_path;
    }

    /**
     * @param  string  $root_path
     */
    public function setRootPath(string $root_path): void
    {
        $this->root_path = $root_path;
    }

    public function captureRequest(): Request
    {
        $request = new Request();

        $request->initRequestFromGlobals();

        return $request;
    }

    public function initRouter(): void
    {
        $this->router->compileRoutes();
    }

    protected function init(): void
    {
        $this->router = new Router();
    }
}
