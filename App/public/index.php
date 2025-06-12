<?php

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\Inicio;
use App\Controllers\MainpageController;
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
$app->router->put("/updateCustomer", [AuthController::class, 'updateCustomer'], ['authApi']);
$app->router->post("/login", [AuthController::class, 'login']);

/* Crud do Home */
$app->router->post("/insertHome", [HomeController::class, 'insertHome']);
$app->router->get("/getAllHome", [HomeController::class, 'getAllHome'], ['authModule']);
$app->router->get("/getSingleHome", [HomeController::class, 'getSingleHome']);
$app->router->put("/updateHome", [HomeController::class, 'updateHome']);
$app->router->delete("/deleteHome", [HomeController::class, 'deleteHome'], ['authModule']);

/* Crud do Admin */
$app->router->post("/login", [AdminController::class, 'loginPost']);
$app->router->get("/login", [AdminController::class, 'loginView']);
$app->router->get("/logout", [AdminController::class, 'logout']);
$app->router->get("/", [MainpageController::class, 'mainPage']);

echo $app->run();