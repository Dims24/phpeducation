<?php
declare(strict_types=1);

namespace App\Http\Middlewares;

use App\Foundation\HTTP\Middlewares\AbstractMiddleware;
use App\Foundation\HTTP\Request;

class AuthMiddleware extends AbstractMiddleware
{
    public function handle(Request $request): ?Request
    {
        dd($request);
        if ($request->get('auth') !== 'auth') {
            throw new \Exception('Forbidden', 401);
        }

        return parent::handle($request);
    }
}
