<?php

namespace MVC\Models;

use MVC\Core\Application;
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
class Role extends TimestampModel
{
    public int $id = 0;
    public string $name = '';
    public int $isActive = self::BOOL_FALSE;
    public ?array $permissions;
    public ?array $permissionIDs;
    public ?array $users;

    public function __construct() {
        $this->permissions = array();
        $this->permissionIDs = array();
        $this->users = array();
        $this->extendAttributes([
            'name',
            'isActive',
            'createdAt',
            'updatedAt',
        ]);
    }

    public static function tableName(): string
    {
        return Constants::$ROLE_TABLE;
    }

    public static function dbFields(): string
    {
        return implode(",", array_map(fn($attr) => "$attr", [
            "id",
            "name",
            "isActive",
            "createdAt",
            "updatedAt",
        ]));
    }

    // return a role object with associated permissions
    public static function getRolePerms($role) {
        $permissionTable = Constants::$PERMISSION_TABLE;
        $rolePermTable = Constants::$ROLE_PERMISSION_TABLE;
        $sql = "SELECT p.perm, p.id FROM $rolePermTable as r
                JOIN $permissionTable as p ON r.permissionID = p.id
                WHERE r.roleID = :roleID";
        $statement = self::prepare($sql);
        $statement->bindParam(":roleID", $role->id, \PDO::PARAM_INT);
        $statement->execute();

        $permissions = [];
        $permissionIDS = [];

        while($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $permissions[$row["perm"]] = true;
            $permissionIDS[] = $row["id"];
        }
        $role->permissions = $permissions;
        $role->permissionIDs = $permissionIDS;
        return $role;
    }

        // return a role object with associated permissions
        public static function getRolePermsByRoleID($roleID) {
            $permissionTable = Constants::$PERMISSION_TABLE;
            $rolePermTable = Constants::$ROLE_PERMISSION_TABLE;
            $sql = "SELECT p.perm, p.id FROM $rolePermTable as r
                    JOIN $permissionTable as p ON r.permissionID = p.id
                    WHERE r.roleID = :roleID";
            $statement = self::prepare($sql);
            $statement->bindParam(":roleID", $roleID, \PDO::PARAM_INT);
            $statement->execute();
            $role = new Role();
            $permissions = [];
            $permissionIDS = [];
    
            while($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
                $permissions[$row["perm"]] = true;
                $permissionIDS[] = $row["id"];
            }
            $role->permissions = $permissions;
            $role->permissionIDs = $permissionIDS;
            return $role;
        }

    // return a role object with associated permissions
    public static function getRoleUsers($role) {
        $userTable = Constants::$USER_TABLE;
        $userRoleTable = Constants::$USER_ROLE_TABLE;
        $sql = "SELECT u.id FROM $userRoleTable as ur
                JOIN $userTable as u ON ur.userID = u.id
                WHERE ur.roleID = :roleID";
        $statement = self::prepare($sql);
        $statement->bindParam(":roleID", $role->id, \PDO::PARAM_INT);
        $statement->execute();

        $users = [];

        while($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $userIDs[] = $row["id"];
        }
        $role->users = $users;
        return $role;
    }

    // check if a permission is set
    public function hasPerm($permission) {
        return isset($this->permissions[$permission]);
    }

    // insert a new role
    public static function insertRole($roleName) {
        $sql = "INSERT INTO Role (name) VALUES (:roleName)";
        $statement = self::prepare($sql);
        $statement->bindValue(":roleName", $roleName);
        return $statement->execute();
    }

    public static function insertRolePermission($roleID, $permissionID) {
        $sql = "INSERT INTO RolePermission (roleID, permissionID) VALUES (:roleID, :permissionID) ON DUPLICATE KEY UPDATE roleID = :roleID, permissionID = :permissionID";
        $statement = self::prepare($sql);
        $statement->bindParam(":roleID", $roleID, \PDO::PARAM_INT);
        $statement->bindParam(":permissionID", $permissionID, \PDO::PARAM_INT);
        return $statement->execute();
    }

    // insert array of roles for specified user id
    public static function insertUserRole($userID, $roleID) {
        $sql = "INSERT INTO UserRole (userID, roleID) VALUES (:userID, :roleID) ON DUPLICATE KEY UPDATE roleID = :roleID, userID = :userID";
        $statement = self::prepare($sql);
        $statement->bindParam(":userID", $userID, \PDO::PARAM_INT);
        $statement->bindParam(":roleID", $roleID, \PDO::PARAM_INT);
        return $statement->execute();;
    }

    // delete array of roles, and all associations
    public static function deleteRoles($roles) {
        $sql = "DELETE r, ur, rp FROM Role as r
                JOIN UserRole as ur on r.id = ur.roleID
                JOIN RolePermission as rp on r.id = rp.roleID
                WHERE r.id = :roleID";
        $statement = self::prepare($sql);
        $statement->bindParam(":roleID", $roleID, \PDO::PARAM_INT);
        foreach ($roles as $roleID) {
            $sth->execute();
        }
        return true;
    }

    // delete ALL roles for specified user id
    public static function deleteUserRoles($userID) {
        $sql = "DELETE FROM UserRole WHERE userID = :userID";
        $statement = self::prepare($sql);
        $statement->bindParam(":userID", $userID, \PDO::PARAM_INT);
        return $statement->execute();
    }

    public static function deleteRolePermissions($roleID, $permissionID) {
        $sql = "DELETE FROM RolePermission WHERE roleID = :roleID AND permissionID = :permissionID";
        $statement = self::prepare($sql);
        $statement->bindParam(":roleID", $roleID, \PDO::PARAM_INT);
        $statement->bindParam(":permissionID", $permissionID, \PDO::PARAM_INT);
        return $statement->execute();
    }

    public static function deleteRoleUsers($roleID) {
        $sql = "DELETE FROM UserRole WHERE roleID = :roleID";
        $statement = self::prepare($sql);
        $statement->bindParam(":roleID", $roleID, \PDO::PARAM_INT);
        return $sth->execute();        
    }

    public function labels(): array
    {
        return [
            'name' => 'Role Name'
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
        $this->isActive = self::BOOL_TRUE;
        return parent::save();
    }
}
?>