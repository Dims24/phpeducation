<?php

namespace App\Models;

use App\Models\Common\BaseModel;

class UsersToken extends BaseModel
{
    protected string $table = 'users_access_token';


    /** @var int */
    public $id;

    /** @var string */
    public $token;

    /** @var int */
    public $user_id;

    /** @var  */
    public $created_at;

    /** @var string */
    public $updated_at;

    /** @var string */
    public $expired_at;
}