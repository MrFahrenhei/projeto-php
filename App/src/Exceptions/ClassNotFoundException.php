<?php

namespace App\Exceptions;

use Exception;

class ClassNotFoundException extends Exception
{
    protected $message = 'Class not found';
    protected $code = 404;
}