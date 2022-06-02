<?php
declare(strict_types=1);

namespace App\Http\Resources\Organisation;

use App\Http\Resources\Common\SingleResource;
use App\Http\Resources\Project\Project;

/**
 * @property \App\Models\Organisation $resource
 */
class Organisation extends SingleResource
{
    public function toArray(): array
    {
        return [
            'organisation_id' => $this->resource->id,
            'organisation_name' => $this->resource->name,
        ];
    }
}
