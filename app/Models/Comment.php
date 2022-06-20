<?php
namespace App\Models;

use App\Models\Common\BaseModel;

class Comment extends BaseModel
{
    protected string $table = "comments";

    /** @var int */
    public $id;

    /** @var string */
    public $user_id;

    /** @var string */
    public $article_id;

    /** @var string */
    public $video_id;

    /** @var string */
    public $comment;

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