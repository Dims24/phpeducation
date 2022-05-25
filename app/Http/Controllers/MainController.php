<?php

namespace App\Http\Controllers;

use App\Foundation\HTTP\Request;
use App\Foundation\HTTP\Response;
use App\Http\Common\BaseController;
use App\Models\Article;

class MainController extends BaseController
{
    public function main(Request $sdjasndjkasbkjd)
    {
        $article = Article::query()->select()->where("name", 1213214)->firstOrFail();
        return $this->respond($article);
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