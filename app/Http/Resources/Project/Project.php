<?php
declare(strict_types=1);

namespace App\Http\Resources\Project;

use App\Http\Resources\Common\SingleResource;
use App\Http\Resources\Organisation\Organisation;

/**
 * @property \App\Models\Project $resource
 */
class Project extends SingleResource
{
    public function toArray(): array
    {
        return [
            'project_id' => $this->resource->id,
            'project_name' => $this->resource->name,
            'organisation' => (new Organisation($this->resource->organisation()))->toArray()
        ];
    }
}
