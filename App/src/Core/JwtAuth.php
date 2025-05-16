<?php

namespace App\Core;

use DateMalformedStringException;
use DateTimeImmutable;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtAuth
{
    public static function generate(array $payload): string
    {
        return JWT::encode($payload, getenv('SECRET_KEY'), 'HS256');
    }
    public static function decode(string $token): ?array
    {
        try {
            return (array) JWT::decode($token, new Key(getenv('SECRET_KEY'), 'HS256'));
        } catch (Exception) {
            return null;
        }
    }

    /**
     * @throws DateMalformedStringException
     */
    public static function generateToken(array $payload): string
    {
        $issuedAt = new DateTimeImmutable();
        $exp = $issuedAt->modify('+6 minutes')->getTimestamp();
        $newPayload = [
            'iss' => $payload['iss'],
            'type' => $payload['type'],
            'sub' => $payload['sub'],
            'name' => $payload['name'],
            'exp' => $exp,
        ];
        return JWT::encode($newPayload, getenv('SECRET_KEY'), 'HS256');
    }
}