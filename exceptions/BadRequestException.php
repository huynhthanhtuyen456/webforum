<?php

namespace MVC\Exceptions;


class BadRequestException extends \Exception
{
    protected $code = 400;
    public $title = "Bad Request";

    public function __construct($message) {
        $this->message = $message;
        parent::__construct();
    } 
}
?>