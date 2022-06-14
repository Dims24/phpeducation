<?php
declare(strict_types=1);

namespace App\Http\Resources\Article;

use App\Http\Resources\Common\SingleResource;
use App\Http\Resources\Organisation\Organisation;

/**
 * @property \App\Models\Article $resource
 */
class Article extends SingleResource
{
    public function toArray(): array
    {
        return [
            'name' => $this->resource->name,
            'description' => $this->resource->description,
//            'organisation' => (new Organisation($this->resource->organisation()))->toArray()
        ];
    }
}
