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
}