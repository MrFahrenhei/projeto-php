<?php

namespace App\Core;

class Request
{
    public function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
    public function isGet(): bool
    {
       return $this->getMethod() === 'get';
    }
    public function isPost(): bool
    {
        return $this->getMethod() === 'post';
    }
    public function getQueryParams(): array
    {
        return $_GET ?? [];
    }
    public function getQueryParam(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }
    public function getPath(): string
    {
       $path = $_SERVER['REQUEST_URI'] ?? "/";
       $position = strpos($path, "?");
       if (!$position) {
          return $path;
       }
       return substr($path, 0, $position);
    }
    public function getBody(): array
    {
        $body = [];
        if (in_array($this->getMethod(), ['get', 'post'])) {
            $body = json_decode(file_get_contents('php://input'), true);
        }
        return is_array($body) ? $body : [];
    }

}