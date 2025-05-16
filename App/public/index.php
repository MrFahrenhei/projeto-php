<?php

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\Inicio;
use App\Core\App;

require_once dirname(__DIR__, 1).'/vendor/autoload.php';

$app = new App(dirname(__DIR__, 1));

$app->router->get("/home", [Inicio::class, 'functionGet']);
$app->router->get("/app", function(){return "this is get app";});
$app->router->post("/app", function(){return "this is post app";});
$app->router->post("/home", [Inicio::class, 'functionPost']);

/* Crud do Customer */
$app->router->post("/sign", [AuthController::class, 'createCustomer']);
$app->router->get("/getSingleCustomer/(:numeric)", [AuthController::class, 'getSingleCustomer']);
$app->router->get("/getAllCustomers", [AuthController::class, 'getAllCustomers'], ['authApi']);
$app->router->post("/updateCustomer", [AuthController::class, 'updateCustomer'], ['authApi']);
$app->router->post("/login", [AuthController::class, 'login']);

/* Crud do Home */
$app->router->post("/insertHome", [HomeController::class, 'insertHome']);
$app->router->get("/getAllHome", [HomeController::class, 'getAllHome'], ['authModule']);
$app->router->get("/getSingleHome", [HomeController::class, 'getSingleHome']);
$app->router->post("/updateHome", [HomeController::class, 'updateHome']);
$app->router->post("/deleteHome", [HomeController::class, 'deleteHome']);

echo $app->run();