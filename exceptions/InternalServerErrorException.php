<?php

namespace MVC\Exceptions;


class InternalServerErrorException extends \Exception
{
    protected $code = 500;
    public $title = "Internal Server Error";

    public function __construct($message) {
        $this->message = $message;
        parent::__construct();
    } 
}
?>