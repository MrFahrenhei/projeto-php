<?php

namespace App\Middleware;

use App\Core\App;
use App\Core\JwtAuth;
use App\Core\Request;
use App\Exceptions\InvalidToken;

class AuthMiddleware implements MiddlewareInterface
{
    /**
     * @throws InvalidToken
     */
    public function handle(Request $request): void
    {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';
        if (!str_starts_with($authHeader, 'Bearer ')) {
            throw new InvalidToken();
        }
        $token = trim(str_replace('Bearer', '', $authHeader));
        $decoded = (new JwtAuth())::decode($token);
        if (!$decoded) {
            throw new InvalidToken();
        }
        $timeLeft = $decoded['exp'] - time();
        if ($timeLeft < 120) {
            $newToken = (new JwtAuth())::generateToken($decoded);
            header('X-Refresh-Token: Bearer ' . $newToken);
        }
    }
}