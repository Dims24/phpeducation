<?php
declare(strict_types=1);

namespace App\Foundation\Router;

use App\Foundation\HTTP\Enums\HTTPMethodsEnum;

class Route
{
    protected array $middlewares = [];
    protected string $name;

    public function __construct(
        public HTTPMethodsEnum $method,
        public string $path,
        public string $pattern,
        public array $variables,
        public string $controller_class,
        public string $controller_method
    )
    {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param  string  $name
     */
    public function name(string $name): void
    {
        $this->name = $name;
    }

    public function middleware(string $middleware_class): self
    {
        if (!in_array($middleware_class, $this->middlewares)) {
            $this->middlewares[] = $middleware_class;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    /**
     * @return array
     */
    public function hasMiddlewares(): bool
    {
        return count($this->middlewares) ? true : false;
    }

    /**
     * @param  array  $middlewares
     */
    public function setMiddlewares(array $middlewares): void
    {
        $this->middlewares = $middlewares;
    }
}
