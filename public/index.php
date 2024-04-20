<?php


use MVC\Controllers\AdminController;
use MVC\Controllers\ContactController;
use MVC\Controllers\HomeController;
use MVC\Controllers\QuestionController;
use MVC\Controllers\ModuleController;
use MVC\Controllers\UserController;
use MVC\Controllers\RegisterController;
use MVC\Controllers\AuthController;
use MVC\Models\User;
use MVC\Core\Application;
use MVC\Core\DotEnv;


define("URL_ROOT", "/");

require_once __DIR__ . '/../autoload.php';
(new DotEnv(__DIR__ . '/../.env'))->load();

$config = [
    'userClass' => \MVC\Models\User::class,
    'db' => [
        'dsn' => getenv('DB_DSN'),
        'user' => getenv('DB_USER'),
        'password' => getenv('DB_PASSWORD'),
    ]
];

$app = new Application(dirname(__DIR__), $config);

// $app->on(Application::EVENT_BEFORE_REQUEST, function(){
//     // echo "Before request from second installation";
// });


$app->router->get("", [HomeController::class, 'index']);

$app->router->get("questions", [QuestionController::class, 'list']);

$app->router->get("ask-questions", [QuestionController::class, 'get']);
$app->router->post("ask-questions", [QuestionController::class, 'post']);

$app->router->get("question/{id}", [QuestionController::class, 'detail']);

$app->router->get("question/{id}/edit", [QuestionController::class, 'update']);
$app->router->post("question/{id}/edit", [QuestionController::class, 'update']);

$app->router->get("question/{id}/delete", [QuestionController::class, 'delete']);

$app->router->get("modules", [ModuleController::class, 'list']);

$app->router->get("users", [UserController::class, 'list']);

$app->router->get("contact", [ContactController::class, 'get']);
$app->router->post("contact", [ContactController::class, 'post']);

$app->router->get("register", [RegisterController::class, 'get']);
$app->router->post("register", [RegisterController::class, 'post']);

$app->router->get("login", [AuthController::class, 'get']);
$app->router->post("login", [AuthController::class, 'post']);

$app->router->get("logout", [AuthController::class, 'logout']);

$app->router->get("profile", [AuthController::class, 'profile']);

$app->router->get("admin", [AdminController::class, 'index']);
$app->router->get("admin/modules/add", [AdminController::class, 'addModule']);
$app->router->post("admin/modules/add", [AdminController::class, 'addModule']);
$app->run();