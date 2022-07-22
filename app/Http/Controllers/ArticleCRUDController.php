<?php

namespace App\Http\Controllers;

use App\Foundation\Database\QueryBuilder;
use App\Foundation\HTTP\Request;
use App\Http\Common\BaseCRUDController;
use App\Http\Resources\Article\ArticleCollection;
use App\Http\Service\Storage;
use App\Models\Article;
use App\Models\File;
use Carbon\Carbon;


class ArticleCRUDController extends BaseCRUDController
{
    public function __construct()
    {
        $this->setCurrentModel(new Article());

//        $this->middleware(\App\Http\Middlewares\AuthMiddleware::class, ['index']);
//        $this->middleware(\App\Http\Middlewares\CheckOwner::class, ['updated', 'show']);

        $this->single_resource = \App\Http\Resources\Article\Article::class;
        $this->collection_resource = ArticleCollection::class;
    }

    public function index()
    {

        $request = new \GuzzleHttp\Psr7\Request();

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
                        if ($files = $request->getFiles()) {
                            $storage = new Storage();

                            foreach ($files as $file) {
                                $path_to_file = $storage->put($file);

                                File::make($path_to_file, $model);
                            }
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
