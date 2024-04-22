<?php
namespace MVC\Forms;


use MVC\Core\Application;
use MVC\Models\User;
use MVC\Models\Role;
use MVC\Helpers\Constants;


class EditUserModelForm extends User
{
    public int $id = 0;
    public string $firstName = '';
    public string $lastName = '';
    public string $emailAddress = '';
    public int $reputation = 0;
    public ?string $birthday = null;
    public ?string $aboutMe = null;
    public int $isActive = self::BOOL_FALSE;
    public int $isSuperAdmin = self::BOOL_FALSE;
    public $loginedAt;
    public $registeredAt;
    public ?array $roles;

    public function __construct()
    {
        $this->extendAttributes([
            'firstName',
            'lastName',
            'emailAddress',
            "registeredAt",
            "isActive",
            "isSuperAdmin",
            "aboutMe",
            "birthday",
            "reputation",
        ]);
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

    public static function getByID($id) {
        $tableName = static::tableName();
        $fields = static::dbFields();
        $sql = "SELECT $fields FROM $tableName WHERE id = :id";
        $statement = self::prepare($sql);
        $statement->bindParam(":id", $id, \PDO::PARAM_INT);
        $statement->execute();
        $user = $statement->fetchObject(static::class);
        $user->initRoles();
        return $user;
    }

    protected function initRoles() {
        $this->roles = array();
        $userRoleTable = Constants::$USER_ROLE_TABLE;
        $roleTable = Constants::$ROLE_TABLE;
        $sql = "SELECT ur.roleID, r.name FROM $userRoleTable as ur
                JOIN $roleTable as r ON r.id = ur.roleID
                WHERE ur.userID = :id";
        $statement = self::prepare($sql);
        $statement->bindParam(":id", $this->id, \PDO::PARAM_INT);
        $statement->execute();
        while($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $this->roles[$row["name"]] = Role::getRolePermsByRoleID($row["roleID"]);
            $this->roles[$row["roleID"]] = true;
        }
    }

    public function getDisplayName(): string
    {
        return $this->firstName.' '.$this->lastName;
    }
}