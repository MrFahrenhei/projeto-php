<?php

namespace App\Enums;

use App\Middleware\AuthApiMiddleware;
use App\Middleware\AuthModuleMiddleware;

enum RouteMiddlewares: string
{
    case customer = AuthApiMiddleware::class;
    case module = AuthModuleMiddleware::class;
}