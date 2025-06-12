<?php

namespace App\Core;

abstract class Controllers
{
    public string $action;
    public string $layout = 'main';

    public function render(mixed $view): false|string
    {
       return App::$app->view->renderView($view);
    }
    public function renderPage(mixed $view, array $params = []): false|string
    {
        return App::$app->view->renderPage($view, $params);
    }
}