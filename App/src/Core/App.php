<?php

namespace App\Core;

use Exception;

class App
{
    private Request $request;
    private Response $response;
    public readonly View $view;
    public readonly Database $db;
    public readonly Router $router;
    public static App $app;
    public static string $ROOT_DIR;

    public function __construct(
        string $rootPath
    )
    {
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        self::$app = $this;
        self::$ROOT_DIR = $rootPath;
        $this->db = new Database();
        $this->view = new View();
//        echo "App ligado".PHP_EOL;
    }

    public function run(): mixed
    {
        try{
//            echo "Entrou no run".PHP_EOL;
            return $this->router->resolve();
        }catch (Exception $e){
            $this->response->setStatusCode(404);
            return $e->getMessage();
        }
    }
}