<?php

namespace MVC\Middlewares;


abstract class BaseMiddleware
{
    abstract public function execute();
}