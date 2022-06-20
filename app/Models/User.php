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

    /** @var string */
    public $hash;

    /** @var string */
    public $token;

    public function articles(bool $execute = true)
    {
        $query = Article::query()->where('user_id', $this->id);

        if ($execute) {
            return $query->first();
        } else {
            return $query->get();
        }
    }

    public function videos(bool $execute = true)
    {
        $query = Video::query()->select()->where('user_id', $this->id);

        if ($execute) {
            return $query->first();
        } else {
            return $query->get();
        }
    }

    public function comments(bool $execute = true)
    {
        $query = Comment::query()->select()->where('user_id', $this->id);

        if ($execute) {
            return $query->first();
        } else {
            return $query->get();
        }
    }
}