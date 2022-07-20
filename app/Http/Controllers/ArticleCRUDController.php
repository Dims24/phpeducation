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

        $this->middleware(\App\Http\Middlewares\AuthMiddleware::class, ['index']);
//        $this->middleware(\App\Http\Middlewares\CheckOwner::class, ['updated', 'show']);

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
                request: $request,
                closure: function (Article $model, string $mode) use ($request) {
                    if ($mode == 'before') {
                        $model->user_id = current_user()->id;
                    } else {
                        foreach ($request->getFiles() as $file) {

                            move_uploaded_file($file['tmp_name'], path("storage\app\\").$file['name']);
                        }
                    }
                }
            )
        );
    }

    public function updated(Request $request, $key)
    {
        return $this->respond(
            $this->parentUpdate(
                request: $request,
                key: $key,
            )
        );
    }

    public function destroy(Request $request, $key)
    {
        return $this->respond(
            $this->parentDestroy(
                request: $request,
                key: $key,
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
