<?php
declare(strict_types=1);

namespace App\Http\Resources\User;

use App\Foundation\Database\Paginator\Paginator;
use App\Http\Resources\Common\CollectionResource;

/**
 * @property Paginator $collection
 */
class UserCollection extends CollectionResource
{
    protected ?string $single_resource = User::class;
}
