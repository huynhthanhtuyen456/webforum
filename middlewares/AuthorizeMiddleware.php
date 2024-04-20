<?php


namespace MVC\Middlewares;


use MVC\Core\Application;
use MVC\Exceptions\ForbiddenException;


class AuthorizeMiddleware extends BaseMiddleware
{
    protected array $actions = [];

    public function __construct($actions = [])
    {
        $this->actions = $actions;
    }

    public function execute()
    {
        if (!Application::isAuthorized()) {
            if (empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)) {
                throw new \MVC\Exceptions\ForbiddenException();
            }
        }
    }
}