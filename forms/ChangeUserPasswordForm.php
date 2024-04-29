<?php
namespace MVC\Forms;


use MVC\Db\DbModel;
use MVC\Helpers\Constants;


class ChangeUserPasswordForm extends DbModel
{
    public int $id = 0;
    public string $password = '';
    public string $oldPassword = '';
    public string $newPassword = '';
    public string $newPasswordConfirm = '';

    public function __construct()
    {
        $this->extendAttributes([
            'password',
        ]);
    }

    public static function tableName(): string
    {
        return Constants::$USER_TABLE;
    }

    public static function dbFields(): string
    {
        return implode(",", array_map(fn($attr) => "$attr", [
            "id",
            "password",
        ]));
    }

    public function labels(): array
    {
        return [
            'oldPassword' => 'Old Password',
            'password' => 'Password',
            'newPassword' => 'New Password',
            'newPasswordConfirm' => 'Confirm New Password',
        ];
    }

    public function rules()
    {
        return [
            'newPassword' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8]],
            'newPasswordConfirm' => [[self::RULE_MATCH, 'match' => 'newPassword']],
        ];
    }
}