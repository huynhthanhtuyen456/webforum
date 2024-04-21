<?php
namespace MVC\Forms;


use MVC\Core\Application;
use MVC\Db\DbModel;
use MVC\Helpers\Constants;


class EditProfileForm extends DbModel
{
    public int $id = 0;
    public string $firstName = '';
    public string $lastName = '';
    public string $emailAddress = '';
    public int $reputation = 0;
    public ?string $birthday = null;
    public ?string $aboutMe = null;
    public $loginedAt;
    public $registeredAt;

    public function __construct()
    {
        $this->extendAttributes([
            'firstName',
            'lastName',
            'emailAddress',
            "registeredAt",
            "aboutMe",
            "birthday",
            "reputation",
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
            "emailAddress",
            "firstName",
            "lastName",
            "loginedAt",
            "registeredAt",
            "password",
            "reputation",
            "aboutMe",
            "birthday",
        ]));
    }

    public function labels(): array
    {
        return [
            'firstName' => 'First name',
            'lastName' => 'Last name',
            'aboutMe' => 'About Me',
            'emailAddress' => 'Email Address',
            'password' => 'Password',
            'reputation' => 'Reputation',
            'birthday' => 'Birthday',
        ];
    }

    public function rules()
    {
        return [
            'firstName' => [self::RULE_REQUIRED],
            'lastName' => [self::RULE_REQUIRED],
            'emailAddress' => [self::RULE_REQUIRED, self::RULE_EMAIL, [
                'class' => self::class
            ]],
        ];
    }

    public function getDisplayName(): string
    {
        return $this->firstName.' '.$this->lastName;
    }
}