<?php

namespace MyProject\Controllers;

use Foundation\HTTP\Request;
use MyProject\Models\Articles\Article;

class MainController
{
    public function main(Request $sdjasndjkasbkjd)
    {
//        $models = Article::all();
        $models = Article::query()->select()->where('name', 'Braun LLC')->;
        dd($models);



    }

    public function sayHello() : mixed
    {
        $model = new Article();
//        $model->name = 'test';
//        $model->save();

        dd($model->getAll());

    }

    public function namedRoute($test, $key)
    {
        $model = new Article();


        dd($model->getRowByNameAndUserId($test, $key));
    }

}