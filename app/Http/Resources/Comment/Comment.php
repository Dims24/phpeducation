<?php
declare(strict_types=1);

namespace App\Http\Resources\Comment;

use App\Http\Resources\Common\SingleResource;
use App\Http\Resources\Organisation\Organisation;

/**
 * @property \App\Models\Comment $resource
 */
class Comment extends SingleResource
{
    public function toArray(): array
    {
        return [
            'comment' => $this->resource->comment,
//            'organisation' => (new Organisation($this->resource->organisation()))->toArray()
        ];
    }
}
