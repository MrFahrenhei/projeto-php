<?php

namespace App\Controllers;

use App\Core\Controllers;

class Home extends Controllers
{
    public function functionGet(): string
    {
       return "This is get inside Home";
    }

    public function functionPost(): string
    {
        return "This is post inside Home";
    }
}