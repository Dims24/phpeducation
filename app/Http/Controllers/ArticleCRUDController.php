<?php

namespace App\Http\Controllers;

use App\Foundation\Database\QueryBuilder;
use App\Foundation\HTTP\Request;
use App\Http\Common\BaseCRUDController;
use App\Http\Resources\Article\ArticleCollection;
use App\Models\Article;


class ArticleCRUDController extends BaseCRUDController
{
    public function __construct()
    {
        $this->setCurrentModel(new Article());

        $this->middleware(\App\Http\Middlewares\AuthMiddleware::class, ['index', 'show']);
//        $this->middleware(\App\Http\Middlewares\TestMiddleware2::class, ['index', 'show']);

        $this->single_resource = \App\Http\Resources\Article\Article::class;
        $this->collection_resource = ArticleCollection::class;
    }

    public function index(Request $request)
    {
        return $this->respond(
            $this->parentIndex(
                request: $request
            )
        );
    }

    public function show(Request $request, $key)
    {
        return $this->respond(
            $this->parentShow(
                request: $request,
                key: $key
            )
        );
    }

    public function store(Request $request)
    {
        return $this->respond(
            $this->parentStore(
                request: $request
            )
        );
    }

    public function updated(Request $request, $key)
    {
        return $this->respond(
            $this->parentUpdate(
                request: $request,
                key: $key
            )
        );
    }

    public function destroy(Request $request, $key)
    {
        return $this->respond(
            $this->parentDestroy(
                request: $request,
                key: $key
            )
        );
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
