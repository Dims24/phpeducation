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
}