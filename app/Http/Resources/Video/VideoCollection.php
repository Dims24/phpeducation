<?php
declare(strict_types=1);

namespace App\Http\Resources\Video;

use App\Foundation\Database\Paginator\Paginator;
use App\Http\Resources\Common\CollectionResource;

/**
 * @property Paginator $collection
 */
class VideoCollection extends CollectionResource
{
    protected ?string $single_resource = Video::class;
}
