<?php
declare(strict_types=1);

namespace App\Http\Middlewares;

use App\Foundation\HTTP\Middlewares\AbstractMiddleware;
use App\Foundation\HTTP\Request;

class TestMiddleware extends AbstractMiddleware
{
    public function handle(Request $request): ?Request
    {

        if ($request->getHeader('Authorization') !== 'authorization') {
            throw new \Exception('Forbidden', 401);
        }

        return parent::handle($request);
    }
}
