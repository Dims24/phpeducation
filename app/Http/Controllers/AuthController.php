<?php

namespace App\Http\Controllers;

use App\Foundation\Database\QueryBuilder;
use App\Foundation\HTTP\Request;
use App\Http\Common\BaseCRUDController;
use App\Http\Resources\Article\ArticleCollection;
use App\Models\Article;
use App\Models\User;


class AuthController extends BaseCRUDController
{
    public function __construct()
    {
        $this->setCurrentModel(new Article());

        $this->single_resource = \App\Http\Resources\Article\Article::class;
        $this->collection_resource = ArticleCollection::class;
    }

    public function signin(Request $request)
    {
        $user = User::login($request->getBody());
        dd($user);
        if(!empty($request->getBody()))
        {
            dd($request->getBody());
        }
        dd($request->getMethod());
    }



    protected function getDefaultOrder(): array|string
    {
        return 'id';
    }

    protected function getQueryBuilder(): QueryBuilder
    {
        return Article::query()->select();
    }
}
