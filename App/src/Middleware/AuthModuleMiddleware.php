<?php

namespace App\Middleware;

use App\Core\Request;
use App\Exceptions\InvalidToken;

class AuthModuleMiddleware implements MiddlewareInterface
{

    public function handle(Request $request): void
    {
        $providedAuth = $_SERVER['HTTP_AUTH_API_KEY'] ?? null;
        $secretKey = getenv('SECRET_KEY');
        if($providedAuth !== $secretKey) {
            throw new InvalidToken();
        }
    }
}