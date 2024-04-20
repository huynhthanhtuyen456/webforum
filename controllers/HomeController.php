<?php
namespace MVC\Controllers;

use MVC\Core\Controller;


class HomeController extends Controller
{
    public function index()
    {
        return $this->render($view='home', $params=[], $title="Home");
    }
}
?>