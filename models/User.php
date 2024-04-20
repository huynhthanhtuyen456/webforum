<?php

namespace MVC\Models;


use MVC\Core\DbModel;
use MVC\Core\UserModel;
use MVC\Helpers\Constants;

/*
$user_table_sql = "CREATE TABLE IF NOT EXISTS `$CONSTANTS->USER_TABLE` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR( 255 ) NOT NULL,
    lastName VARCHAR( 255 ) NOT NULL,
    emailAddress VARCHAR (255) NOT NULL,
    password VARCHAR (2000) NOT NULL,
    isActive BOOLEAN NOT NULL,
    isSuperAdmin BOOLEAN NOT NULL,
    registeredAt DATETIME NOT NULL,
    reputation INT NOT NULL,
    birthday DATE NULL,
    aboutMe TEXT (1500),
    loginedAt DATETIME NULL
    );
";
*/
class User extends UserModel
{
    public int $id = 0;
    public string $firstName = '';
    public string $lastName = '';
    public string $emailAddress = '';
    public int $reputation = 0;
    public string $birthday = '';
    public string $aboutMe = '';
    public string $password = '';
    public string $passwordConfirm = '';
    public int $isActive = self::BOOL_FALSE;
    public int $isSuperAdmin = self::BOOL_FALSE;
    public $loginedAt;
    public $registeredAt;

    public function __construct() {
        $this->extendAttributes([
            'firstName',
            'lastName',
            'emailAddress',
            'password',
            "registeredAt",
            "isActive",
            "isSuperAdmin",
            "reputation"
        ]);
    }

    public function getLoginedAt(): string {
        return $this->loginedAt;
    }

    public function getRegisteredAt(): string {
        return $this->registeredAt;
    }

    public function getBirthday(): string {
        return $this->birthday;
    }

    public function setLoginedAt($dateTimeString) {
        // Update the DateTime attribute with a new value
        $datetime = new \DateTime($dateTimeString);
        $this->loginedAt = $datetime->format('Y-m-d\TH:i:s');
    }

    public function setRegisteredAt($dateTimeString) {
        // Update the DateTime attribute with a new value
        $datetime = new \DateTime($dateTimeString);
        $this->registeredAt = $datetime->format('Y-m-d\TH:i:s');
    }

    public function setBirthday($dateString) {
        // Update the Date attribute with a new value
        $date = new \Date($dateString);
        $this->birthday = $date->format('Y-m-d');
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
            "isActive",
            "isSuperAdmin",
            "reputation",
        ]));
    }

    public function labels(): array
    {
        return [
            'firstName' => 'First name',
            'lastName' => 'Last name',
            'emailAddress' => 'Email Address',
            'password' => 'Password',
            'passwordConfirm' => 'Password Confirm'
        ];
    }

    public function rules()
    {
        return [
            'firstName' => [self::RULE_REQUIRED],
            'lastName' => [self::RULE_REQUIRED],
            'emailAddress' => [self::RULE_REQUIRED, self::RULE_EMAIL, [
                self::RULE_UNIQUE, 'class' => self::class
            ]],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8]],
            'passwordConfirm' => [[self::RULE_MATCH, 'match' => 'password']],
        ];
    }

    public function save()
    {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        $this->setRegisteredAt("now");
        $this->isActive = self::BOOL_TRUE;
        $this->isSuperAdmin = self::BOOL_FALSE;
        return parent::save();
    }

    public function getDisplayName(): string
    {
        return $this->firstName.' '.$this->lastName;
    }
}