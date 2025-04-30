<?php

namespace App\Core;

class Response
{
//    public function __construct()
//    {
//        echo "Response ligado".PHP_EOL;
//    }

    public function setStatusCode(int $code): void
    {
       http_response_code($code);
    }
}