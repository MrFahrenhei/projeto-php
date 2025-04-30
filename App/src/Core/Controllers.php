<?php

namespace App\Core;

abstract class Controllers
{
    public string $action;

    public function render(mixed $view): false|string
    {
       return App::$app->view->renderView($view);
    }
}