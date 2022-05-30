<?php

namespace App\Http\Controllers;

use App\Foundation\HTTP\Request;
use App\Http\Common\BaseController;
use App\Models\Article;
use App\Models\Project;

class ArticleCRUDController extends BaseController
{
    public function index(Request $request)
    {
        $projects = Article::all();

        return $this->respond($projects);
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
}
