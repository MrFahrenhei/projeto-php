<?php

namespace App\Enums;

use App\Middleware\AuthMiddleware;

enum RouteMiddlewares: string
{
    case customer = AuthMiddleware::class;
}