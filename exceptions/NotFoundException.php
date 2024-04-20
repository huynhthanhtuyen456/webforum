<?php

namespace MVC\Exceptions;

use MVC\Application;


class NotFoundException extends \Exception
{
    protected $message = 'Page not found';
    protected $code = 404;
    public $title = "Not found";
}
?>