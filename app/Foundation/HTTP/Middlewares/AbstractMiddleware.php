<?php
declare(strict_types=1);

namespace App\Foundation\HTTP\Middlewares;

use App\Foundation\HTTP\Request;

abstract class AbstractMiddleware implements MiddlewareContract
{
    protected ?MiddlewareContract $next = null;

    public function next(MiddlewareContract $middleware): MiddlewareContract
    {
        $this->next = $middleware;

        return $middleware;
    }

    public function handle(Request $request): ?Request
    {
        if (!is_null($this->next)) {
            return $this->next->handle($request);
        }

        return null;
    }
}
