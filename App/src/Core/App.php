<?php

namespace App\Core;

use App\Models\Customer;
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
    public string $layout = 'main';
    public ?Controllers $controller = null;
    public ?Model $user;
    public Session $session;
    public string $userClass;

    public function __construct(
        string $rootPath
    )
    {
//      header("Access-Control-Allow-Origin: https://api.avetools.com.br");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS");
        header('Access-Control-Allow-Headers: Authorization, Content-Type');
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }
        $this->userClass = Customer::class;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        self::$app = $this;
        self::$ROOT_DIR = $rootPath;
        $this->db = new Database();
        $this->view = new View();
        $this->session = new Session();

        $primaryValue = $this->session->get('user');
        if($primaryValue){
            $primaryKey = (new $this->userClass())->primaryKey();
            $this->user = (new $this->userClass())->findOne([$primaryKey => $primaryKey]);
        }else{
            $this->user = null;
        }
    }

    public static function isGuest(): bool
    {
       return !self::$app->user;
    }

    public function run(): mixed
    {
        try{
            return $this->router->resolve();
        }catch (Exception $e){
            $this->response->setStatusCode(404);
            return $e->getMessage();
        }
    }
}