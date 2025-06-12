<?php

namespace App\Middleware;

use App\Core\Request;
use App\Exceptions\InvalidToken;

class AuthModuleMiddleware implements MiddlewareInterface
{

    /**
     * @throws InvalidToken
     */
    public function handle(Request $request): void
    {
        $providedAuth = $request->getHeader('Auth-Api-Key');
        $secretKey = getenv('SECRET_KEY');
        if($providedAuth !== $secretKey) {
            throw new InvalidToken();
        }
    }
}