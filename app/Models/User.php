<?php
namespace App\Models;

use App\Models\Common\BaseModel;

class User extends BaseModel
{
    protected string $table = 'users';

    /** @var int */
    public $id;

    /** @var string */
    public $email;

    /** @var string */
    public $password;
}