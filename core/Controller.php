<?php


namespace MVC\Core;

use MVC\Middlewares\BaseMiddleware;


class Controller
{
    private $model;
    public string $layout = 'base';
    public string $action = '';
    protected int $limit = 4;
    public int $currentPage = 1;
    public int $offset = 0;

    protected array $middlewares = [];

    public function setLayout($layout): void
    {
        $this->layout = $layout;
    }

    public function render($view, $params = [], $title = ""): string
    {
        return Application::$app->router->renderView($view, $params, $title);
    }

    public function registerMiddleware(BaseMiddleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    public function getPageOffset(): int
    {
        $this->offset = ($this->currentPage - 1) * $this->limit;
        return $this->offset;
    }

    public function getLimit(): int {
        return $this->limit;
    }
}