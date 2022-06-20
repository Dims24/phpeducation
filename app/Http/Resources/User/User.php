<?php
declare(strict_types=1);

namespace App\Http\Resources\User;

use App\Http\Resources\Article\Article;
use App\Http\Resources\Comment\Comment;
use App\Http\Resources\Common\SingleResource;
use App\Http\Resources\Video\Video;

/**
 * @property \App\Models\User $resource
 */
class User extends SingleResource
{
    public function toArray(): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->email,
            'password' => $this->resource->password,
            'articles' => Article::collection($this->resource->articles(false)),
            'videos' => Video::collection($this->resource->videos(false)),
            'comments' => Comment::collection($this->resource->comments(false)),
        ];
    }

}
