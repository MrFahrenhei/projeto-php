<?php
namespace App\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    protected $message = 'Url not found';
    protected $code = 404;
}