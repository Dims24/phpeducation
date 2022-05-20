<?php

namespace MyProject\Controllers;

use MyProject\Models\Articles\Article;

class MainController
{
    public function main()
    {
        echo 'Ебать приятно';
    }
    public function sayHello() : mixed
    {
        $model = new Article();

        var_dump($model->getTitle());
        $result = json_encode($model->getTitle());
        return $result;
    }
}