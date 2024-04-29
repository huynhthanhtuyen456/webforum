<?php
namespace MVC\Controllers;

use MVC\Core\Controller;


class UserController extends Controller
{
    public function list()
    {
        return $this->render($view='users', $params=[], $title="Users");
    }
}
?>