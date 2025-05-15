<?php

namespace App\Core;

use App\Enums\RouteWildcard;
use App\Exceptions\NotFoundException;

class Router
{
    protected array $routes = [];
    public function __construct(
        public readonly Request $request,
        public readonly Response $response,
    ){}

    public function replaceWildcardWithPattern(string $uriToReplace): string
    {
        $patterns = [
            '(:numeric)' => RouteWildcard::numeric->value,
            '(:alpha)' => RouteWildcard::alpha->value,
            '(:any)' => RouteWildcard::any->value,
        ];
        return str_replace(array_keys($patterns), array_values($patterns), $uriToReplace);
    }
    public function get(string $path, mixed $callback, ?array $middleware = null): void
    {
        $newPath = $this->replaceWildcardWithPattern($path);
        $this->routes['get'][$newPath] = [
            'callback' => $callback,
            'middleware' => $middleware,
        ];
    }
    public function post(string $path, mixed $callback, ?array $middleware = null): void
    {

        $newPath = $this->replaceWildcardWithPattern($path);
        $this->routes['post'][$newPath] = [
            'callback' => $callback,
            'middleware' => $middleware,
        ];
    }
    /**
     * @throws NotFoundException
     */
    public function resolve(): mixed
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        foreach ($this->routes[$method] as $pattern => $route) {
            $regex = "#^" . $pattern . "$#";
            if (preg_match($regex, $path, $matches)) {
                array_shift($matches);
                $callback = $route['callback'];
                $middlewares = $route['middleware'] ?? [];
                foreach ($middlewares as $middleware) {
                    $middlewareClass = "\\App\\Middleware\\" . ucfirst($middleware) . "Middleware";
                    if (!class_exists($middlewareClass)) {
                        throw new \Exception("Middleware '$middlewareClass' not found.");
                    }
                    $instance = new $middlewareClass();
                    $instance->handle($this->request);
                }
                if (is_array($callback)) {
                    $controller = new $callback[0]();
                    $controller->action = $callback[1];
                    $callback[0] = $controller;
                }
                return call_user_func_array($callback, array_merge([$this->request], $matches));
            }
        }
        throw new NotFoundException();
    }
}