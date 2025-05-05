<?php

namespace App\Exceptions;

use Exception;

class HomeNotFoundException extends Exception
{
    protected $message = 'Home not found';
    protected $code = 404;
}