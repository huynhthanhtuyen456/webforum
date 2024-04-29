<?php

namespace MVC\Models;

use MVC\Helpers\Constants;


/*
$role_table_sql = "CREATE TABLE IF NOT EXISTS ".Constants::$ROLE_TABLE." (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR( 255 ) NOT NULL,
    isActive BOOLEAN NOT NULL,
    createdAt DATETIME NOT NULL,
    updatedAt DATETIME NOT NULL
    );
";
*/
class Permission extends TimestampModel
{
    public int $id = 0;
    public string $perm = '';
    public int $isActive = self::BOOL_FALSE;

    public function __construct() {
        $this->extendAttributes([
            'perm',
            'isActive',
            'createdAt',
            'updatedAt',
        ]);
    }

    public static function tableName(): string
    {
        return Constants::$PERMISSION_TABLE;
    }

    public static function dbFields(): string
    {
        return implode(",", array_map(fn($attr) => "$attr", [
            "id",
            "perm",
            "isActive",
            "createdAt",
            "updatedAt",
        ]));
    }

    public function labels(): array
    {
        return [
            'perm' => 'Permission Name'
        ];
    }

    public function rules()
    {
        return [
            'perm' => [self::RULE_REQUIRED],
        ];
    }

    public function save()
    {
        $this->setCreatedAt("now");
        $this->setUpdatedAt("now");
        $this->isActive = self::BOOL_TRUE;
        return parent::save();
    }
}
?>