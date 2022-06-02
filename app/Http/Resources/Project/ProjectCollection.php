<?php
declare(strict_types=1);

namespace App\Http\Resources\Project;

use App\Foundation\Database\Paginator\Paginator;
use App\Http\Resources\Common\CollectionResource;

/**
 * @property Paginator $collection
 */
class ProjectCollection extends CollectionResource
{
    protected ?string $single_resource = Project::class;
}
