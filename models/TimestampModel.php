<?php

namespace MVC\Models;

use MVC\Db\DbModel;

abstract class TimestampModel extends DbModel
{
    public string $createdAt = "";
    public string $updatedAt = "";

    public function __construct() {
        $this->extendAttributes([
            'createdAt',
            'updatedAt',
        ]);
    }

    public function getCreatedAt(): string {
        return $this->createdAt;
    }

    public function getUpdatedAt(): string {
        return $this->updatedAt;
    }

    public function setCreatedAt($dateTimeString) {
        // Update the DateTime attribute with a new value
        $datetime = new \DateTime($dateTimeString);
        $this->createdAt = $datetime->format('Y-m-d\TH:i:s');
    }

    public function setUpdatedAt($dateTimeString) {
        // Update the DateTime attribute with a new value
        $datetime = new \DateTime($dateTimeString);
        $this->updatedAt = $datetime->format('Y-m-d\TH:i:s');
    }
}
?>