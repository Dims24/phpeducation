<?php
namespace App\Models;

use App\Models\Common\BaseModel;

class Video extends BaseModel
{
    protected string $table = "videos";

    /** @var int */
    public $id;

    /** @var string */
    public $user_id;

    /** @var string */
    public $name;

    /** @var string */
    public $url;

    public function user(bool $execute = true)
    {
        $query = User::query()->select()->where('id', $this->user_id);

        if ($execute) {
            return $query->first();
        } else {
            return $query->get();
        }
    }
}