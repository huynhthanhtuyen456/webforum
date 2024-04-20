<?php
namespace MVC\Controllers;

use MVC\Core\Controller;


class UserController extends Controller
{
    public function list()
    {
        // $this->setTitle("Users");
        // $this->render('users');
        return $this->render($view='users', $params=[], $title="Users");
    }
}
?>