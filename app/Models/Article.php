<?php
namespace App\Models;

use App\Models\Common\BaseModel;

class Article extends BaseModel
{
    protected string $table = "articles";

    /** @var int */
    public $id;

    /** @var string */
    public $user_id;

    /** @var string */
    public $name;

    /** @var string */
    public $description;
}