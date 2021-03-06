<?php

namespace App\Http\Common;

use App\Foundation\Database\Paginator\Paginator;
use App\Foundation\HTTP\Middlewares\MiddlewareContract;
use App\Foundation\HTTP\Response;
use App\Http\Resources\Common\CollectionResource;
use App\Http\Resources\Common\SingleResource;
use App\Models\Common\BaseModel;
use \ReflectionClass;

abstract class BaseController
{
    protected array $methods;

    /**
     * Controller base model.
     *
     * @var BaseModel
     */
    protected BaseModel $current_model;

    /**
     * Array of options. Meta information for controller actions.
     *
     * @var array
     */
    protected array $options = [];

    /**
     * @var MiddlewareContract[]
     */
    protected array $middlewares = [];

    /**
     * @param array $methods
     */
//    public function __construct(array $methods)
//    {
//        $this->getReflectionMethod();;
//        $this->methods = array_keys($this->middlewares);;
//    }


    /**
     * @return MiddlewareContract[$method]
     */
    public function getMiddlewares(string $method): array
    {
        return $this->middlewares[$method];
    }

    /**
     * @param MiddlewareContract[] $middlewares
     */
    public function setMiddlewares(array $middlewares): void
    {
        $this->middlewares = $middlewares;
    }

    public function setMiddleware(string $middleware): void
    {
        $this->middlewares[] = $middleware;
    }

    /**
     * @return array
     */
    public function hasMiddleware(string $method): bool
    {

        foreach (array_keys($this->middlewares) as $value){
            if ($value == $method){
                return true;
            }
        }
        return false;
    }
    /**
     * @return BaseModel
     */
    public function getCurrentModel(): BaseModel
    {
        return $this->current_model;
    }

    /**
     * @param  BaseModel  $current_model
     */
    public function setCurrentModel(BaseModel $current_model): void
    {
        $this->current_model = $current_model;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param  array  $options
     */
    public function setOptions(array $options): void
    {
        $this->options = $options;
    }

    /**
     * Set option.
     *
     * @param string $key
     * @param mixed $value
     * @return array
     */
    protected function setOption(string $key, mixed $value): array
    {
        return helper_array_set($this->options, $key, $value);
    }

    /**
     * Get option.
     *
     * @param  string  $key
     * @return mixed
     */
    protected function getOption(string $key): mixed
    {
        $result = helper_array_get($this->options, $key);

        return $result == null ? false : $result;
    }

    protected function initFunction($function_meta = []): void
    {
        if (isset($function_meta['options']))
            $this->setOptions($function_meta['options']);
    }

    public function respond(mixed $data, int $code = 200, array $headers = []): Response
    {
        if ($data instanceof Paginator) {
            $response = new Response([
                'data' => $data->getData(),
                'meta' => $data->getPaginationInfo(),
            ], $code, $headers);

            return $response;
        } elseif($data instanceof CollectionResource) {
            $response = new Response($data->toArray(), $code, $headers);

            return $response;
        } elseif($data instanceof SingleResource) {
            $response = new Response(['data' => $data->toArray()], $code, $headers);

            return $response;
        } else {
            if ($data instanceof BaseModel) {
                $data = $data->toArray();
            }

            $response = new Response([
                'data' => $data,
            ], $code, $headers);
            return $response;
        }
    }

    public function middleware($middleware_class, array $methods = null): void
    {
        if (!$methods){
            $this->getReflectionMethod();
            $methods = array_keys($this->middlewares);

        }

        foreach ($methods as $method) {
                $this->middlewares[$method][] = $middleware_class;
            }

        if (!$method) {
            $this->middlewares[] = $middleware_class;
        }
    }


    protected function getReflectionMethod(): void
    {
        $reflection_class = new ReflectionClass($this);
        $array = $reflection_class->getMethods();
        foreach ($array as $method) {
            if ($method->class == $reflection_class->name)
            {
                if ($method->name == '__construct'){
                    continue;
                }
                $this->middlewares[$method->name] = null;
            }
        }

    }

}
