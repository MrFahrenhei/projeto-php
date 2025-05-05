<?php

namespace App\Core;

use App\Exceptions\NotFoundException;

class Router
{
    protected array $routes = [];
    public function __construct(
        public readonly Request $request,
        public readonly Response $response,
    ){}
    public function get(string $path, mixed $callback): void
    {
        $this->routes['get'][$path] = $callback;
    }
    public function post(string $path, mixed $callback): void
    {
        $this->routes['post'][$path] = $callback;
    }
    /**
     * @throws NotFoundException
     */
    public function resolve(): mixed
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        $callback = $this->routes[$method][$path] ?? false;
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