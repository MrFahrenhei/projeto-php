<?php

namespace App\Core;

class Request
{
//    public function __construct()
//    {
//        echo "Request ligado".PHP_EOL;
//    }

    public function getMethod(): string
    {
//        echo "Entrou no getMethod".PHP_EOL;
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
    public function getPath(): string
    {
//        echo "Entrou no getPath".PHP_EOL;
       $path = $_SERVER['REQUEST_URI'] ?? "/";
       $position = strpos($path, "?");
       if (!$position) {
          return $path;
       }
       return substr($path, 0, $position);
    }

    public function getBody(): ?array
    {
        $body = [];
        if (in_array($this->getMethod(), ['get', 'post'])) {
            $body = json_decode(file_get_contents('php://input'), true);
        }
        return (!is_null($body)) ? $body : [];
    }

}