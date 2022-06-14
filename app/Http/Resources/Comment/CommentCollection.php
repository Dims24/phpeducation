<?php
declare(strict_types=1);

namespace App\Http\Resources\Comment;

use App\Foundation\Database\Paginator\Paginator;
use App\Http\Resources\Common\CollectionResource;

/**
 * @property Paginator $collection
 */
class CommentCollection extends CollectionResource
{
    protected ?string $single_resource = Comment::class;
}
