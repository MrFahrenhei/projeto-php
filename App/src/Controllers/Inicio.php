<?php

namespace App\Controllers;

use App\Core\Controllers;

class Inicio extends Controllers
{
    public function functionGet(): string
    {
       return "This is get inside Inicio";
    }

    public function functionPost(): string
    {
        return "This is post inside Inicio";
    }
}