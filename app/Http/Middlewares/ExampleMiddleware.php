<?php
declare(strict_types=1);

namespace App\Http\Middlewares;

use App\Foundation\HTTP\Middlewares\AbstractMiddleware;
use App\Foundation\HTTP\Request;

class ExampleMiddleware extends AbstractMiddleware
{
    public function handle(Request $request): ?Request
    {
        if ($request->get('example') !== 'example') {
            throw new \Exception('Forbidden', 401);
        }

        return parent::handle($request);
    }
}
