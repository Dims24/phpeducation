<?php
namespace App\Models;

use App\Models\Common\BaseModel;

class Organisation extends BaseModel
{
    protected string $table = "organisations";

    /** @var string */
    public $id;

    /** @var string */
    public $owner_id;

    /** @var string */
    public $name;

    public function projects(bool $execute = true)
    {
        $query = Project::query()->select()->where('organisation_id', $this->id);

        if ($execute) {
            return $query->get();
        } else {
            return $query;
        }
    }
}
