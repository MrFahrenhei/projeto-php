<?php

namespace App\Core;

class Request
{
    private array $attributes = [];
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
    public function getHeader(string $name): ?string
    {
        $headers = getallheaders();
        foreach ($headers as $key => $value) {
            if (strtolower($key) === strtolower($name)) {
                return $value;
            }
        }
        return null;
    }
    public function setAttribute(string $key, mixed $value): void
    {
        $this->attributes[$key] = $value;
    }
    public function getAttribute(string $key, mixed $default = null): mixed
    {
        return $this->attributes[$key] ?? $default;
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
        return $position === false ? $path : substr($path, 0, $position);
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