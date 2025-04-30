<?php

use App\Controllers\AuthController;
use App\Controllers\Home;
use App\Core\App;

require_once dirname(__DIR__, 1).'/vendor/autoload.php';

$app = new App(dirname(__DIR__, 1));
$app->router->get("/home", [Home::class, 'functionGet']);
$app->router->get("/app", function(){return "this is get app";});
$app->router->post("/app", function(){return "this is post app";});
$app->router->post("/home", [Home::class, 'functionPost']);
$app->router->post("/sign", [AuthController::class, 'createCustomer']);
echo $app->run();