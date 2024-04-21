<?php
namespace MVC\Controllers;

use MVC\Core\Controller;
use MVC\Core\Request;
use MVC\Core\Response;
use MVC\Core\Application;
use MVC\Forms\LoginForm;


class AuthController extends Controller
{
    public function get(Request $request)
    {
        $loginForm = new LoginForm();
        $this->setLayout('auth');
        return $this->render('login', [
            'model' => $loginForm
        ]);
    }

    public function post(Request $request)
    {
        $loginForm = new LoginForm();
        $loginForm->loadData($request->getBody());
        if ($loginForm->validate() && $loginForm->login()) {
            Application::$app->response->redirect('/');
        }
        return $this->render('login', [
            'model' => $loginForm
        ], "Login");
    }

    public function logout(Request $request, Response $response)
    {
        Application::$app->logout();
        $response->redirect('/');
    }
}
?>