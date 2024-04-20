<?php

namespace MVC\Models;

use MVC\Helpers\Constants;

/*
$module_table_sql = "CREATE TABLE IF NOT EXISTS `$CONSTANTS->MODULE_TABLE` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR( 255 ) NOT NULL,
    createdAt DATETIME NOT NULL,
    updatedAt DATETIME NOT NULL
    );
";
*/
class Module extends TimestampModel
{
    public int $id = 0;
    public string $name = '';

    public function __construct() {
        $this->extendAttributes(["name", "createdAt", "updatedAt"]);
    }

    public static function tableName(): string
    {
        return Constants::$MODULE_TABLE;
    }

    public static function dbFields(): string
    {
        return implode(",", array_map(fn($attr) => "$attr", [
            "id",
            "name",
            "createdAt",
            "updatedAt"
        ]));
    }

    public function labels(): array
    {
        return [
            'name' => 'Module Name'
        ];
    }

    public function rules()
    {
        return [
            'name' => [self::RULE_REQUIRED],
        ];
    }

    public function save()
    {
        $this->setCreatedAt("now");
        $this->setUpdatedAt("now");
        return parent::save();
    }
}