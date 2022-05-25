<?php

namespace App\Foundation;

use App\Common\Patterns\Singleton;
use App\Foundation\Exception\ExceptionHandler;
use App\Foundation\HTTP\Request;
use Foundation\Router\Router;
use JetBrains\PhpStorm\NoReturn;

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

            $this->terminate();
        } catch (\Throwable $exception) {
            ExceptionHandler::handleException($exception);
        }
    }

    #[NoReturn] public function terminate()
    {
        exit(0);
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
