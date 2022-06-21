<?php
namespace App\Models;

use App\Foundation\HTTP\Request;
use App\Models\Common\BaseModel;
use App\Models\Common\Interface\HasOwnerKey;

class Article extends BaseModel implements HasOwnerKey
{
    protected string $table = "articles";

    /** @var int */
    public $id;

    /** @var string */
    public $user_id;

    /** @var string */
    public $name;

    /** @var string */
    public $article;

    public function user(bool $execute = true)
    {
        $query = User::query()->select()->where('id', $this->user_id);

        if ($execute) {
            return $query->first();
        } else {
            return $query->get();
        }
    }

    public function getOwnerId(): int
    {
        return $this->user_id;
    }
}