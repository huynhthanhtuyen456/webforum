<?php


namespace MVC\Db;

use MVC\Core\Application;
use MVC\Core\Model;


abstract class DbModel extends Model
{
    const BOOL_TRUE = 1;
    const BOOL_FALSE = 0;
    protected array $attributes = [];
    abstract public static function tableName(): string;
    abstract public static function dbFields(): string;
    public int $id;

    public static function primaryKey(): string
    {
        return 'id';
    }

    public function save()
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();

        $params = array_map(fn($attr) => ":$attr", $attributes);
        $statement = self::prepare("INSERT INTO $tableName (" . implode(",", $attributes) . ") 
                VALUES (" . implode(",", $params) . ")");

        foreach ($attributes as $attribute) {
            $statement->bindValue(":$attribute", $this->{$attribute});
        }

        try {
            $statement->execute();
            $this->id = self::getInsertedId();
        } catch (\Exception $e) {
            throw new \MVC\Exceptions\InternalServerErrorException($e->getMessage());
        }
    
        return true;
    }

    public function delete()
    {
        $tableName = $this->tableName();
        $statement = self::prepare("DELETE FROM $tableName WHERE id = :id");

        $statement->bindParam(":id", $this->id, \PDO::PARAM_INT);

        try {
            $statement->execute();
        } catch (\Exception $e) {
            throw new \MVC\Exceptions\InternalServerErrorException($e->getMessage());
        }
    
        return true;
    }

    public function getUpdateData()
    {
        $updateData = [];
        $attributes = $this->attributes();
        foreach ($attributes as $attribute) {
            $updateData[$attribute] = $this->{$attribute};
        }
        $updateData["id"] = $this->id;
        return $updateData;
    }

    public static function update(array $data, array $where = [])
    {
        $tableName = static::tableName();

        $update_params = [];
        foreach ($data as $key => $value) {
            $update_params[] = "$key = :$key";
        }
        $update_params = implode(', ', $update_params);

        $attributes = array_keys($where);
        $filters = implode("AND", array_map(fn($attr) => "$attr = :$attr", $attributes));

        $prepare_stm = $where ? "UPDATE $tableName SET $update_params WHERE $filters" : "UPDATE $tableName SET $update_params WHERE id = :id";

        $statement = self::prepare($prepare_stm);

        foreach ($data as $key => &$value) {
            $statement->bindParam(":$key", $value);
        }

        foreach ($where as $key => $item) {
            $statement->bindValue(":$key", $item);
        }

        try {
            $statement->execute();
        } catch (\Exception $e) {
            throw new \MVC\Exceptions\InternalServerErrorException($e->getMessage());
        }

        return $statement->fetchObject(static::class);
    }

    public static function prepare($sql): \PDOStatement
    {
        return Application::$app->db->prepare($sql);
    }

    public static function getInsertedId(): int
    {
        return Application::$app->db->getInsertedId();
    }

    public static function findOne($where)
    {
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $sql = implode(" AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));

        $fields = static::dbFields();

        $statement = self::prepare("SELECT $fields FROM $tableName WHERE $sql");
        foreach ($where as $key => $item) {
            $statement->bindValue(":$key", $item);
        }
        $statement->execute();

        return $statement->fetchObject(static::class);
    }

    public static function findAll(array $where = [], int $limit = 0, int $offset = 0, string $oderBy = '')
    {
        $tableName = static::tableName();
        
        if($where) {
            $attributes = array_keys($where);
            $sql = implode(" AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        }
        
        $fields = static::dbFields();

        $prepare_stm = $where ? "SELECT $fields FROM $tableName WHERE $sql" : "SELECT $fields FROM $tableName";
        list($field, $order) = $oderBy ? explode(' ', $oderBy) : '';
        $prepare_stm = !empty($oderBy) && str_contains($fields, $field) && (str_contains($order, "ASC") || str_contains($order, "DESC")) ? $prepare_stm.' ORDER BY '.$oderBy : $prepare_stm;

        $prepare_stm .= $limit ? " LIMIT :limit" : "";

        $prepare_stm .= $offset ? " OFFSET :offset" : "";

        $statement = self::prepare($prepare_stm);
        foreach ($where as $key => $item) {
            $statement->bindValue(":$key", $item);
        }

        if ($limit) {
            $statement->bindParam(":limit", $limit, \PDO::PARAM_INT);
        }

        if ($offset) {
            $statement->bindValue(":offset", $offset, \PDO::PARAM_INT);
        }

        try {
            $statement->execute();            
        } catch (\PDOException $e) {
            throw new \MVC\Exceptions\InternalServerErrorException($e->getMessage());
        }

        return $statement->fetchAll();
    }

    public static function countAll(array $where = [])
    {
        $tableName = static::tableName();

        $attributes = array_keys($where);
        $sql = implode(" AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));

        $prepare_stm = $where ? "SELECT COUNT('id') FROM $tableName WHERE $sql" : "SELECT COUNT('id') FROM $tableName";

        $statement = self::prepare($prepare_stm);
        foreach ($where as $key => $item) {
            $statement->bindValue(":$key", $item);
        }

        try {
            $statement->execute();            
        } catch (\PDOException $e) {
            throw new \MVC\Exceptions\InternalServerErrorException($e->getMessage());
        }

        return $statement->fetchColumn();
    }

    public function attributes(): array
    {
        return $this->attributes;
    }

    protected function extendAttributes(array $attributes)
    {
        $result = array_merge($this->attributes, $attributes);
        $this->attributes = $result;
    }
}