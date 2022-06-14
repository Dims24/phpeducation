<?php
declare(strict_types=1);

namespace App\Http\Resources\Video;

use App\Http\Resources\Common\SingleResource;
use App\Http\Resources\Organisation\Organisation;

/**
 * @property \App\Models\Video $resource
 */
class Video extends SingleResource
{
    public function toArray(): array
    {
        return [
            'name' => $this->resource->name,
            'url' => $this->resource->url,
//            'organisation' => (new Organisation($this->resource->organisation()))->toArray()
        ];
    }
}
