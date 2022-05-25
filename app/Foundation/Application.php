<?php

namespace App\Foundation;

use App\Foundation\Exception\ExceptionHandler;
use App\Foundation\HTTP\Request;
use Foundation\Router\Router;

class Application
{
    protected Router $router;

    public function __construct(
        ?Router $router = null,
    )
    {
        if (!is_null($router)) {
            $this->router = $router;

        } else {
            $this->router = new Router(); #$this->router = [3]
        }
    }

    #Функция запуска приложения
    public function run()
    {
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
}