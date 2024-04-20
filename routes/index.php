<?php

use MVC\Controllers\AdminController;
use MVC\Controllers\HomeController;
use MVC\Controllers\QuestionController;
use MVC\Controllers\TagController;
use MVC\Controllers\UserController;
use MVC\Controllers\ThankYouController;

use MVC\Router;

$router = new Router();


$router->get(URL_ROOT, [HomeController::class, 'index']);
$router->get(URL_ROOT."questions", [QuestionController::class, 'list']);
$router->get(URL_ROOT."ask-questions", [QuestionController::class, 'create']);
$router->get(URL_ROOT."question/{$id}", [QuestionController::class, 'detail']);
$router->get(URL_ROOT."tags", [TagController::class, 'list']);
$router->get(URL_ROOT."users", [UserController::class, 'list']);
$router->get(URL_ROOT."thank-you", [ThankYouController::class, 'index']);
$router->get(URL_ROOT."admin", [AdminController::class, 'index']);

$router->dispatch();
?>