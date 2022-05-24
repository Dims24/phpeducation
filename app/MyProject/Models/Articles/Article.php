<?php
namespace MyProject\Models\Articles;
use MyProject\Models\BaseModel;
use MyProject\Models\Users\User;

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




//    private $data = [
//        'id',
//        'name',
//        'description',
//        'user_id',
//    ];



}