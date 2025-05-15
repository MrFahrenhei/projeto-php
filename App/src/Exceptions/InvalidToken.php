<?php

namespace App\Exceptions;


use Exception;

class InvalidToken extends Exception
{
    protected $message = 'Missing or invalid token';
    protected $code = 401;
}