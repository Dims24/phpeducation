<?php

use Foundation\HTTP\Request;

class Application
{
    protected \Router\Router $router;

    public function __construct(
        ?\Router\Router $router = null,
    )
    {
        if (!is_null($router)) {
            $this->router = $router;
        } else {
            $this->router = new \Router\Router();
        }
    }

    public function run()
    {
        $this->initRouter();
        $response = $this->router->execute(
            $this->captureRequest()
        );

        return $response;
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