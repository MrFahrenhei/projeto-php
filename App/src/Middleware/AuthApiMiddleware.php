<?php

namespace App\Middleware;

use App\Core\JwtAuth;
use App\Core\Request;
use App\Exceptions\InvalidToken;

class AuthApiMiddleware implements MiddlewareInterface
{
    /**
     * @throws InvalidToken
     */
    public function handle(Request $request): void
    {
        $authHeader = $request->getHeader('Authorization');
        if(empty($authHeader)) {
            throw new InvalidToken();
        }
        if (!str_starts_with($authHeader, 'Bearer ')) {
            throw new InvalidToken();
        }
        $token = trim(str_replace('Bearer', '', $authHeader));
        $decoded = (new JwtAuth())::decode($token);
        if (!$decoded) {
            throw new InvalidToken();
        }
        $request->setAttribute('user', $decoded);
        $timeLeft = $decoded['exp'] - time();
//        echo $timeLeft;
        if ($timeLeft < 250) {
            $newToken = (new JwtAuth())::generateToken($decoded);
            header('Access-Control-Expose-Headers: X-Refresh-Token'); // <- necessário
            header('X-Refresh-Token: Bearer ' . $newToken);
        }
    }
}