<?php

namespace App\Http\Service;


use App\Foundation\HTTP\Request;
use App\Models\UsersToken;

class UserTokenManipulation
{

    protected string $token;

    public function hasToken(Request$request)
    {
        $token = $request->getHeader('Authorization')['Authorization'];
        $this->token = str_replace('Bearer ','',$token);
        $new = $this->checkExpiredToken($token);
        $user_token = UsersToken::query()->select()->where('token', $this->token)->first();
        dd($user_token->token);

    }

    protected function checkExpiredToken($token)
    {
        $user_token = UsersToken::query()->select()->where('token', $this->token)->first();

        $date = date('Y-m-d H:i:s');

        dd($user_token->expired_at);

    }




}