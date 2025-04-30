<?php

namespace App\Core;

use App\Exceptions\NotFoundException;

class Router
{
    protected array $routes = [];
    public function __construct(
        public readonly Request $request,
        public readonly Response $response,
    )
    {
//        echo "Router ligado".PHP_EOL;
    }

    public function get(string $path, mixed $callback): void
    {
//        echo "Entrou no get".PHP_EOL;
        $this->routes['get'][$path] = $callback;
    }

    public function post(string $path, mixed $callback): void
    {
//        echo "Entrou no post".PHP_EOL;
        $this->routes['post'][$path] = $callback;
    }

    /**
     * @throws NotFoundException
     */
    public function resolve(): mixed
    {
//        echo "Entrou no resolve".PHP_EOL;
//        echo "routes: ".json_encode($this->routes).PHP_EOL;
        $path = $this->request->getPath();
//        echo "path: ".json_encode($path).PHP_EOL;
        $method = $this->request->getMethod();
//        echo " method: ".json_encode($method).PHP_EOL;
        $callback = $this->routes[$method][$path] ?? false;
//        echo "callback: ".json_encode($callback).PHP_EOL;
        if(!$callback) {
            throw new NotFoundException();
        }
        if(is_array($callback)) {
            $controller = new $callback[0]();
            $controller->action = $callback[1];
            $callback[0] = $controller;
        }
        return call_user_func($callback, $this->request, $this->response);
    }
}