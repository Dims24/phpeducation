<?php
declare(strict_types=1);

namespace App\Http\Resources\Organisation;

use App\Foundation\Database\Paginator\Paginator;
use App\Http\Resources\Common\CollectionResource;

/**
 * @property Paginator $collection
 */
class OrganisationCollection extends CollectionResource
{
    protected ?string $single_resource = Organisation::class;
}
