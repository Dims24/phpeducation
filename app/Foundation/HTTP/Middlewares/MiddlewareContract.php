<?php
declare(strict_types=1);

namespace App\Foundation\HTTP\Middlewares;

use App\Foundation\HTTP\Request;

interface MiddlewareContract
{
    public function next(MiddlewareContract $middleware): MiddlewareContract;

    public function handle(Request $request): ?Request;
}
