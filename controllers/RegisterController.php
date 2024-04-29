<?php
namespace MVC\Controllers;

use MVC\Core\Controller;
use MVC\Core\Request;
use MVC\Core\Application;
use MVC\Models\User;


class RegisterController extends Controller
{
    public function get()
    {
        $registerModel = new User();
        $this->setLayout('auth');
        return $this->render('register', [
            'model' => $registerModel
        ], "Register");
    }

    public function post(Request $request)
    {
        $registerModel = new User();
        $this->setLayout('auth');
        $registerModel->loadData($request->getBody());
        if ($registerModel->validate() && $registerModel->save()) {
            Application::$app->session->setFlash('success', 'You registered Greenwich Forum Account successfully. Thanks for registering!');
            Application::$app->response->redirect('/login');
        }

        return $this->render('register', [
            'model' => $registerModel
        ]);
    }
}
?>