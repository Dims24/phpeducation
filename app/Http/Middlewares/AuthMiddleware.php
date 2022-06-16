<?php
declare(strict_types=1);

namespace App\Http\Middlewares;

use App\Foundation\HTTP\Middlewares\AbstractMiddleware;
use App\Foundation\HTTP\Request;
use App\Http\Service\Exceptions\AccessDeniedException;
use App\Http\Service\Exceptions\UserTokenExpiredException;
use App\Http\Service\UserTokenService;

class AuthMiddleware extends AbstractMiddleware
{
    /**
     * @throws UserTokenExpiredException
     * @throws AccessDeniedException
     */
    public function handle(Request $request): ?Request
    {
        $token_service = UserTokenService::getInstance();

        $token_service->checkTokenByRequest($request);

        return parent::handle($request);
    }
}
