<?php

namespace App\Http\Service;


use App\Common\Patterns\Singleton;
use App\Foundation\HTTP\Request;
use App\Http\Service\Exceptions\AccessDeniedException;
use App\Http\Service\Exceptions\UserTokenExpiredException;
use App\Models\UsersToken;

/**
 * @method static UserTokenService getInstance()
 */
class UserTokenService extends Singleton
{
    protected ?UsersToken $currentUserToken = null;

    /**
     * @return UsersToken|null
     */
    public function getCurrentUserToken(): ?UsersToken
    {
        return $this->currentUserToken;
    }

    /**
     * @param UsersToken|null $currentUserToken
     */
    public function setCurrentUserToken(?UsersToken $currentUserToken): void
    {
        $this->currentUserToken = $currentUserToken;
    }


    /**
     * @param Request $request
     * @return bool
     * @throws AccessDeniedException
     * @throws UserTokenExpiredException
     */
    public function checkTokenByRequest(Request $request): bool
    {
        if (!($token_from_request = $this->getTokenFromRequestHeader($request))) {
            throw new AccessDeniedException();
        }

        if (!($token_model = $this->getTokenModelByRequestToken($token_from_request))) {
            throw new AccessDeniedException();
        }

        $this->removeExpiredTokensByUserId($token_model->user_id);

        if (!($token_model = $this->getTokenModelByRequestToken($token_from_request))) {
            throw new UserTokenExpiredException();
        }

        if (is_null($this->currentUserToken)) {
            $this->setCurrentUserToken($token_model);
        }

        return true;
    }

    protected function getTokenModelByRequestToken(string $token_from_request): ?UsersToken
    {
        $user_token = UsersToken::query()->select()->where('token', $token_from_request)->first();

        // do something

        return $user_token;
    }

    protected function removeExpiredTokensByUserId(int $user_id): void
    {
        /** @var UsersToken[] $expired_tokens */
        $expired_tokens = UsersToken::query()
            ->select()
            ->where('user_id', $user_id)
            ->where('expired_at', '<', now()->format('Y-m-d H:i:s'))
            ->get()
        ;

        foreach ($expired_tokens as $expired_token) {
            $expired_token->delete();
        }
    }

    /**
     * @param Request $request
     * @return string|bool
     */
    protected function getTokenFromRequestHeader(Request $request): string|bool
    {
        if ($request->hasHeader('Authorization')) {
            $token = $request->getHeader('Authorization')['Authorization'];
            $token = str_replace('Bearer ', '', $token);

            // do something

            return $token;
        } else {
            return false;
        }
    }


}