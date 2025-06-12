<?php

namespace App\Controllers;

use App\Core\Controllers;

class MainpageController extends Controllers
{
    public function mainPage(): string
    {
        $params = [
            'name' => "beraldo",
            'pipoca' => "eu gosto",
            'ola' => 'não deixa'
        ];
        return $this->renderPage('home', $params);
    }
}