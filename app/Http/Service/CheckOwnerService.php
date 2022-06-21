<?php

namespace App\Http\Service;

use App\Http\Service\Exceptions\AccessDeniedException;
use App\Models\Common\Interface\HasOwnerKey;
use App\Models\User;

class CheckOwnerService
{
    /**
     * @throws AccessDeniedException
     */
    public function checkOwner(HasOwnerKey $model, User $user): void
    {
        $key_user_model = $model->getOwnerId();

        if ($key_user_model != $user->id) {
            throw new AccessDeniedException();
        }
    }
}