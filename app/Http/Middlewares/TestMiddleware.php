<?php
declare(strict_types=1);

namespace App\Http\Middlewares;

use App\Foundation\HTTP\Middlewares\AbstractMiddleware;
use App\Foundation\HTTP\Request;
use App\Models\UsersToken;

class TestMiddleware extends AbstractMiddleware
{
    public function handle(Request $request): ?Request
    {

        $token = $request->getHeader('Authorization');
        $token = str_replace('Bearer ','',$token);

        $user_token = UsersToken::find($request->getHeader('Authorization'));
        dd($user_token);


        if ($request->getHeader('Authorization') !== 'authorization') {
            throw new \Exception('Forbidden', 401);
        }

        return parent::handle($request);
    }
}
