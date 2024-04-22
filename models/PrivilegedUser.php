<?php

namespace MVC\Models;

use MVC\Helpers\Constants;


class PrivilegedUser extends User
{
    private ?array $roles = [];

    public function __construct() {
        parent::__construct();
    }

    // override User method
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

    public static function getByEmailAddress($emailAddress) {
        $tableName = static::tableName();
        $fields = static::dbFields();
        $sql = "SELECT $fields FROM $tableName WHERE emailAddress = :emailAddress";
        $statement = self::prepare($sql);
        $statement->bindValue(":emailAddress", $emailAddress);
        $statement->execute();
        $user = $statement->fetchObject(static::class);
        $user->initRoles();
        return $user;
    }

    // populate roles with their associated permissions
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
        }
    }

    // insert array of role for specified user id
    public static function insertUserRole($userID, $roleID) {
        $sql = "INSERT INTO UserRole (userID, roleID) VALUES (:userID, :roleID) ON DUPLICATE KEY UPDATE userID = :userID, roleID = :roleID";
        $statement = self::prepare($sql);
        $statement->bindParam(":userID", $userID, \PDO::PARAM_INT);
        $statement->bindParam(":roleID", $roleID, \PDO::PARAM_INT);
        return $statement->execute();
    }

    // check if user has a specific privilege
    public function hasPrivilege($perm) {
        foreach ($this->roles as $role) {
            if ($role->hasPerm($perm)) {
                return true;
            }
        }
        return false;
    }

    // check if a user has a specific role
    public function hasRole($roleName) {
        return isset($this->roles[$roleName]);
    }

    // insert a new role permission association
    public static function insertPerm($roleID, $permissionID) {
        $sql = "INSERT INTO RolePermission (roleID, permissionID) VALUES (:roleID, :permissionID)";
        $statement = self::prepare($sql);
        $statement->bindParam(":roleID", $roleID, \PDO::PARAM_INT);
        $statement->bindParam(":permissionID", $permissionID, \PDO::PARAM_INT);
        return $statement->execute();
    }

    // delete ALL role permissions
    public static function deletePerms() {
        $sql = "TRUNCATE RolePermission";
        $statement = self::prepare($sql);
        return $statement->execute();
    }
}