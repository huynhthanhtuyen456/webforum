<?php


use MVC\Controllers\AdminController;
use MVC\Controllers\ContactController;
use MVC\Controllers\HomeController;
use MVC\Controllers\QuestionController;
use MVC\Controllers\ModuleController;
use MVC\Controllers\UserController;
use MVC\Controllers\RegisterController;
use MVC\Controllers\AuthController;
use MVC\Controllers\ProfileController;
use MVC\Controllers\SearchQuestionsController;
use MVC\Core\Application;
use MVC\Core\DotEnv;


define("URL_ROOT", "/");

require_once __DIR__ . '/../autoload.php';
(new DotEnv(__DIR__ . '/../.env'))->load();

$config = [
    'userClass' => \MVC\Models\PrivilegedUser::class,
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
$app->router->post("question/answers/add", [QuestionController::class, 'addAnswer']);
$app->router->get("questions/search", [SearchQuestionsController::class, 'search']);

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

/* Profile Management */
$app->router->get("profile", [ProfileController::class, 'index']);
$app->router->get("profile/change-password", [ProfileController::class, 'changePassword']);
$app->router->post("profile/change-password", [ProfileController::class, 'changePassword']);
$app->router->get("profile/edit", [ProfileController::class, 'editProfile']);
$app->router->post("profile/edit", [ProfileController::class, 'editProfile']);
$app->router->get("profile/contacts/add", [ProfileController::class, 'addContact']);
$app->router->post("profile/contacts/add", [ProfileController::class, 'addContact']);
$app->router->get("profile/contacts/{id}/edit", [ProfileController::class, 'editContact']);
$app->router->post("profile/contacts/{id}/edit", [ProfileController::class, 'editContact']);
$app->router->get("profile/contacts/{id}/delete", [ProfileController::class, 'deleteContact']);
$app->router->get("profile/answers/{id}/edit", [ProfileController::class, 'editAnswer']);
$app->router->post("profile/answers/{id}/edit", [ProfileController::class, 'editAnswer']);
$app->router->get("profile/answers/{id}/delete", [ProfileController::class, 'deleteAnswer']);
/* End Define Profile Management */

/* Define Admin Path for Data Management */
$app->router->get("admin", [AdminController::class, 'index']);

/* Module Management */
$app->router->get("admin/modules/add", [AdminController::class, 'addModule']);
$app->router->post("admin/modules/add", [AdminController::class, 'addModule']);

$app->router->get("admin/modules/{id}/edit", [AdminController::class, 'editModule']);
$app->router->post("admin/modules/{id}/edit", [AdminController::class, 'editModule']);
$app->router->get("admin/modules/{id}/delete", [AdminController::class, 'deleteModule']);
/* End Module Management */

/* Question Management */
$app->router->get("admin/questions/add", [AdminController::class, 'addQuestion']);
$app->router->post("admin/questions/add", [AdminController::class, 'addQuestion']);
$app->router->get("admin/questions/{id}/edit", [AdminController::class, 'editQuestion']);
$app->router->post("admin/questions/{id}/edit", [AdminController::class, 'editQuestion']);
$app->router->get("admin/questions/{id}/delete", [AdminController::class, 'deleteQuestion']);
/* End Question Management */

/* Answers Management */
$app->router->get("admin/answers/{id}/edit", [AdminController::class, 'editAnswer']);
$app->router->post("admin/answers/{id}/edit", [AdminController::class, 'editAnswer']);
$app->router->get("admin/answers/{id}/delete", [AdminController::class, 'deleteAnswer']);
/* End Answers Management */

/* User Management */
$app->router->get("admin/users/add", [AdminController::class, 'addUser']);
$app->router->post("admin/users/add", [AdminController::class, 'addUser']);
$app->router->get("admin/users/{id}/edit", [AdminController::class, 'editUser']);
$app->router->post("admin/users/{id}/edit", [AdminController::class, 'editUser']);
$app->router->get("admin/users/{id}/delete", [AdminController::class, 'deleteUser']);
$app->router->get("admin/users/{id}/change-password", [AdminController::class, 'changePassword']);
$app->router->post("admin/users/{id}/change-password", [AdminController::class, 'changePassword']);
/* End User Management */

/* User Management */
$app->router->get("admin/contacts/add", [AdminController::class, 'addContact']);
$app->router->post("admin/contacts/add", [AdminController::class, 'addContact']);
$app->router->get("admin/contacts/{id}/edit", [AdminController::class, 'editContact']);
$app->router->post("admin/contacts/{id}/edit", [AdminController::class, 'editContact']);
$app->router->get("admin/contacts/{id}/delete", [AdminController::class, 'deleteContact']);
/* End User Management */

/* Role Management */
$app->router->get("admin/roles/add", [AdminController::class, 'addRole']);
$app->router->post("admin/roles/add", [AdminController::class, 'addRole']);
$app->router->get("admin/roles/{id}/edit", [AdminController::class, 'editRole']);
$app->router->post("admin/roles/{id}/edit", [AdminController::class, 'editRole']);
$app->router->get("admin/roles/{id}/delete", [AdminController::class, 'deleteRole']);
/* End Role Management */

/* Permission Management */
$app->router->get("admin/permissions/add", [AdminController::class, 'addPermission']);
$app->router->post("admin/permissions/add", [AdminController::class, 'addPermission']);
$app->router->get("admin/permissions/{id}/edit", [AdminController::class, 'editPermission']);
$app->router->post("admin/permissions/{id}/edit", [AdminController::class, 'editPermission']);
$app->router->get("admin/permissions/{id}/delete", [AdminController::class, 'deletePermission']);
/* End Permission Management */

/* End Definition Admin Path for Data Management */
$app->run();