<?php

namespace MVC\Core;

use MVC\Db\DbModel;


abstract class BaseModel extends DbModel
{
    private $createdAt;
    private $updatedAt;

    public function getCreatedAt() {
        return $this->createdAt->format('Y-m-d\TH:i:s');
    }

    public function getUpdatedAt() {
        return $this->updatedAt->format('Y-m-d\TH:i:s');
    }

    public function setCreatedAt($dateTimeString) {
        // Update the DateTime attribute with a new value
        $this->createdAt = new DateTime($dateTimeString);
    }

    public function setUpdatedAt($dateTimeString) {
        // Update the DateTime attribute with a new value
        $this->updatedAt = new DateTime($dateTimeString);
    }
}

?>