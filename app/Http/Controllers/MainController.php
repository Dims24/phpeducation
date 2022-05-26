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
        $article = Article::query()->select()->get();

        return $this->respond($article);
    }

    public function sayHello() : mixed
    {
        $model = new Article();
//        $model->name = 'test';
//        $model->save();

        dd($model->getAll());

    }

    public function show(Request $request, Article $article)
    {

        return $this->respond([
            'article' => $article,
            'router_variables' => $request->getRouterVariables()
        ]);
    }

}