<?php

namespace App\Http\Controllers\Auth;

use App\Foundation\HTTP\Request;
use App\Foundation\HTTP\Response;
use App\Http\Common\BaseController;
use App\Http\Controllers\Auth\Exceptions\RegistrationValidationException;
use App\Http\Controllers\UsersTokenCRUDController;
use App\Models\User;
use App\Models\UsersToken;


class Authorization extends BaseController
{

    public function register(Request $request): Response
    {
        $this->validationUser($request,'register');
        $user_email = $request->get('email');
        $user_password = $request->get('password');
        $user = new User();
        $user->email = $user_email;
        $user->password = password_hash($user_password, PASSWORD_DEFAULT);
        $user->save();

        return $this->respond($user, 201);
    }

    public function login(Request $request): Response
    {
        $this->validationUser($request, 'login');
        $user_email = $request->get('email');
        $user = User::query()->select()->where('email', $user_email)->first();
        $user_id = $user->id;
        $user_token = sha1(random_bytes(100)) . sha1(random_bytes(100));
        $user_token_table = new UsersToken();
        $user_token_table->token = $user_token;
        $user_token_table->user_id = $user_id;
        $date = new \DateTime('now');
        $user_token_table->created_at = $date->format('Y-m-d H:i:s');
        $user_token_table->expired_at = $date->add(new \DateInterval('P1D'))->format('Y-m-d H:i:s');

        $user_token_table->save();

        return $this->respond(data: ["token"=>$user_token,"user"=>$user]);
    }


    /**
     * @throws RegistrationValidationException
     */
    private function validationUser(Request $request, $mode = null)
    {
        if (!$request->has('email')) {
            throw new RegistrationValidationException('Не указан Email');
        }

        $user_email = $request->get('email');

        if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
            throw new RegistrationValidationException('Введен некорректный Email');
        }

        if (!$request->has('password')) {
            throw new RegistrationValidationException('Не указан пароль');
        }
        $user_password = $request->get('password');

        if (!$user_password || strlen($user_password) < 6) {
            throw new RegistrationValidationException('Указан некорректный пароль');
        }

        if($mode == "register")
        {
            if (User::query()->select()->where('email', $user_email)->count()) {
                throw new RegistrationValidationException('Пользователь с таким Email уже существует');
            }

            if (!$request->has('password_confirmation')) {
                throw new RegistrationValidationException('Не указано подтверждение пароля');
            }

            $user_password_confirmation = $request->get('password_confirmation');

            if ($user_password != $user_password_confirmation) {
                throw new RegistrationValidationException('Указанные пароли не совпадают');
            }
        }

        if($mode == "login")
        {
            if(!password_verify($user_password, $this->gerHash($user_email))){
                throw new RegistrationValidationException('Указан неверный пароль');
            }
        }
    }

    private function gerHash($user_email): string
    {
        $user = User::query()->select()->where('email', $user_email)->first();
        return $user->password;
    }


}