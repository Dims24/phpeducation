<?php

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
//        $class = '\Application';
//        $method = 'main';
//        $test2 = new $class();
//        $test2->$method();
        $this->chek();

    }

    public function initRouter()
    {
        $this->router->compileRoutes();
    }

    public function chek()
    {
        $request = new \Router\Request();
        $request= $request->getReguest();
        $chek = $this->router->getCompiledRoutes();

        foreach ($chek as $method=>$array)
        {
            if ($request["method"]==$method)
            {
                foreach ($array as $item)
                {
                    if ($item["url"]==$request["url"])
                    {
                        return $this->router->controllermethod($item["action"]);
                    }
                }
            }
        }
    }
}