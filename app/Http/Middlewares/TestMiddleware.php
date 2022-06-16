<?php
declare(strict_types=1);

namespace App\Http\Middlewares;

use App\Foundation\HTTP\Middlewares\AbstractMiddleware;
use App\Foundation\HTTP\Request;
use App\Http\Service\UserTokenManipulation;
use App\Models\UsersToken;

class TestMiddleware extends AbstractMiddleware
{
    public function handle(Request $request): ?Request
    {
        $token = new UserTokenManipulation();

        if (!$token->hasToken($request)) {
            throw new \Exception('Forbidden', 401);
        }

        return parent::handle($request);
    }
}
