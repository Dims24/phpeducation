<?php
namespace App\Models;

use App\Models\Common\BaseModel;

class Project extends BaseModel
{
    protected string $table = "projects";

    /** @var string */
    public $id;

    /** @var string */
    public $organisation_id;

    /** @var string */
    public $name;

    /** @var string */
    public $description;
}
