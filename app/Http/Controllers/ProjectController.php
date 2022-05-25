<?php

namespace App\Http\Controllers;

use App\Foundation\HTTP\Request;
use App\Foundation\HTTP\Response;
use App\Http\Common\BaseController;
use App\Models\Article;
use App\Models\Project;

class ProjectController extends BaseController
{
    public function index()
    {
        $projects = Project::all();

        return $this->respond($projects);
    }
}
