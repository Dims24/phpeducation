<?php

namespace MyProject\Controllers;

use Foundation\HTTP\Request;
use MyProject\Models\Articles\Article;

class MainController
{
    public function main(Request $sdjasndjkasbkjd)
    {
        dd($sdjasndjkasbkjd->get('test'));
    }

    public function sayHello() : mixed
    {
        $model = new Article();

        var_dump($model->getTitle());
        $result = json_encode($model->getTitle());
        return $result;
    }
}