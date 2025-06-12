<?php

namespace App\Core;

class View
{
    public string $title = '';
    public function renderView(mixed $params): false|string
    {
        header('Content-type: application/json');
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        return json_encode($params, JSON_UNESCAPED_UNICODE);
    }

    public function renderPage($view, array $params = []): array|false|string
    {
        $viewContent = $this->renderOnlyView($view, $params);
        $layoutContent = $this->layoutContent();
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }
    protected function layoutContent(): false|string
    {
        $layout = App::$app->layout;
        if(App::$app->controller) {
            $layout = App::$app->controller->layout;
        }
        ob_start();
        include_once App::$ROOT_DIR . "/src/Views/layouts/{$layout}.php";
        return ob_get_clean();
    }
    protected function renderOnlyView(string $view, array $params): false|string
    {
        foreach($params as $key => $value) {
            $$key  = $value;
        }
        ob_start();
        include_once App::$ROOT_DIR . "/src/Views/{$view}.php";
        return ob_get_clean();
    }
}