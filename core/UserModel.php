<?php


namespace MVC\Core;

use MVC\Db\DbModel;


abstract class UserModel extends DbModel
{
    abstract public function getDisplayName(): string;
}