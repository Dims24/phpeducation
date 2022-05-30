<?php

namespace App\Http\Controllers;

use App\Foundation\Database\QueryBuilder;
use App\Foundation\HTTP\Request;
use App\Http\Common\BaseCRUDController;
use App\Models\Article;


class ArticleCRUDController extends BaseCRUDController
{
    public function index(Request $request)
    {
        return $this->respond(
            $this->parentIndex(
                request: $request,
            )
        );
    }

    public function show(Request $request, Article $article)
    {

        return $this->respond($article);
    }

    public function store(Request $request)
    {
        $article = new Article();

        foreach ($article->getFillable() as $column) {
            if ($value = $request->get($column)) {
                $article->$column = $value;
            }
        }

        $article->save();

        return $this->respond($article);
    }

    public function updated(Request $request, Article $article)
    {
        foreach ($article->getFillable() as $column) {
            if ($value = $request->get($column)) {
                $article->$column = $value;
            }
        }

        $article->save();

        return $this->respond($article);
    }

    public function destroy(Request $request, Article $article)
    {
        $article->delete();
        return $this->respond('ok');
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
