<?php

namespace App\Http\Controllers;

use App\Foundation\Database\QueryBuilder;
use App\Foundation\HTTP\Request;
use App\Foundation\HTTP\Response;
use App\Http\Common\BaseController;
use App\Http\Common\BaseCRUDController;
use App\Models\Article;
use App\Models\Project;

class ProjectController extends BaseCRUDController
{
    public function __construct()
    {
        $this->setCurrentModel(new Project());
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
        return '-name';
    }

    protected function getQueryBuilder(): QueryBuilder
    {
        return Project::query()->select();
    }
}
