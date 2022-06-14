<?php
declare(strict_types=1);

namespace App\Http\Resources\User;

use App\Http\Resources\Common\SingleResource;
use App\Http\Resources\Organisation\Organisation;

/**
 * @property \App\Models\User $resource
 */
class User extends SingleResource
{
    public function toArray(): array
    {
        return [
            'name' => $this->resource->email,
            'password' => $this->resource->password,
            'hash' => $this->resource->hash,
            'token' => $this->resource->token
//            'organisation' => (new Organisation($this->resource->organisation()))->toArray()
        ];
    }
}
