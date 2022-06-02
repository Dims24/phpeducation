<?php

namespace App\Http\Controllers;

use App\Foundation\Database\QueryBuilder;
use App\Foundation\HTTP\Request;
use App\Http\Common\BaseCRUDController;
use App\Http\Resources\Organisation\OrganisationCollection;
use App\Models\Organisation;

class OrganisationController extends BaseCRUDController
{
    public function __construct()
    {
        $this->setCurrentModel(new Organisation());

        $this->single_resource = \App\Http\Resources\Organisation\Organisation::class;
        $this->collection_resource = OrganisationCollection::class;
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
        return Organisation::query()->select();
    }
}
