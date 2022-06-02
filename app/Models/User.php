<?php
namespace App\Models;

use App\Models\Common\BaseModel;

class User extends BaseModel
{
    protected string $table = 'users';

    /** @var int */
    public $id;

    /** @var string */
    public $first_name;

    /** @var string */
    public $last_name;

    /** @var string */
    public $email;
}