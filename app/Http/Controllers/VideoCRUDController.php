<?php

namespace App\Http\Controllers;

use App\Foundation\Database\QueryBuilder;
use App\Foundation\HTTP\Request;
use App\Http\Common\BaseCRUDController;
use App\Http\Exceptions\AccessDeniedException;
use App\Http\Resources\Video\VideoCollection;
use App\Models\Video;


class VideoCRUDController extends BaseCRUDController
{
    public function __construct()
    {
        $this->setCurrentModel(new Video);

        $this->single_resource = \App\Http\Resources\Video\Video::class;
        $this->collection_resource = VideoCollection::class;
    }

    public function index(Request $request)
    {
        return $this->respond(
            $this->parentIndex(
                request: $request,
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
                closure: function (Video $model, string $mode) {
                    if ($mode == 'before') {
                        $model->user_id = current_user()->id;
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
                closure: function (Video $model, string $mode) {
                    if ($mode == 'before') {
                        if ($model->user_id !== current_user()->id) {
                            throw new AccessDeniedException();
                        }
                    }
                }
            )
        );
    }

    public function destroy(Request $request, $key)
    {
        return $this->respond(
            $this->parentDestroy(
                request: $request,
                key: $key,
                closure: function (Video $model, string $mode) {
                    if ($mode == 'before') {
                        if ($model->user_id !== current_user()->id) {
                            throw new AccessDeniedException();
                        }
                    }
                }
            )
        );
    }

    protected function getDefaultOrder(): array|string
    {
        return 'id';
    }

    protected function getQueryBuilder(): QueryBuilder
    {
        return Video::query()->select();
    }
}
