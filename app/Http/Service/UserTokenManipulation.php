<?php

namespace App\Http\Service;


use App\Foundation\HTTP\Request;
use App\Http\Service\Exceptions\ExaminationUserTokenExeption;
use App\Models\UsersToken;

class UserTokenManipulation
{

    protected string $token;

    public function hasToken(Request$request)
    {
        $token = $request->getHeader('Authorization')['Authorization'];
        $this->token = str_replace('Bearer ','',$token);
        $this->checkExpiredToken();
        return true;
    }

    protected function checkExpiredToken()
    {
        $user_token = UsersToken::query()->select()->where('token', $this->token)->first();
        if (is_null($user_token)) {
            throw new ExaminationUserTokenExeption("Пользователь не авторизован");
        }
        $date = strtotime(date('Y-m-d H:i:s'));
        $date_expired = strtotime($user_token->expired_at);
        if ($date_expired > $date){
            return $user_token;
        }else{
            $user_token->delete();
            throw new ExaminationUserTokenExeption();
        }
    }
}