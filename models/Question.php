<?php

namespace MVC\Models;

use MVC\Helpers\Constants;

/*
$question_table_sql = "CREATE TABLE IF NOT EXISTS `$CONSTANTS->QUESTION_TABLE` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    thread VARCHAR( 255 ) NOT NULL,
    content TEXT NOT NULL,
    image VARCHAR (5028) NULL,
    createdAt DATETIME NOT NULL,
    updatedAt DATETIME NOT NULL,
    isActive BOOLEAN NOT NULL,
    authorID INT NOT NULL,
    CONSTRAINT fkPostAuthor
        FOREIGN KEY(authorID) 
        REFERENCES User(id) ON DELETE CASCADE,
    moduleID INT NOT NULL,
    CONSTRAINT fkPostModule
        FOREIGN KEY(moduleID) 
        REFERENCES Module(id)
    );
";
*/
class Question extends TimestampModel
{
    public int $id = 0;
    public string $thread = '';
    public string $content = '';
    public string $image = '';
    public int $isActive = self::BOOL_FALSE;
    public int $authorID = 0;
    public $moduleID = 1;

    public function __construct() {
        parent::__construct();
        $this->extendAttributes([
            "thread",
            "content",
            "isActive",
            "image",
            "authorID",
            "moduleID"
        ]);
    }

    public static function tableName(): string
    {
        return Constants::$QUESTION_TABLE;
    }

    public static function dbFields(): string
    {
        return implode(",", array_map(fn($attr) => "$attr", [
            "id",
            "thread",
            "content",
            "image",
            "isActive",
            "createdAt",
            "updatedAt",
            "authorID",
            "moduleID",
        ]));
    }

    public function labels(): array
    {
        return [
            'thread' => 'Thread',
            'content' => 'Content',
            'image' => 'Upload Your Image',
        ];
    }

    public function rules()
    {
        return [
            'thread' => [self::RULE_REQUIRED],
            'content' => [self::RULE_REQUIRED],
        ];
    }

    public function save()
    {
        try {
            $this->setCreatedAt("now");
            $this->setUpdatedAt("now");
            $this->isActive = self::BOOL_TRUE;
            return parent::save();
        } catch (\Exception $e) {
            throw new \MVC\Exceptions\InternalServerErrorException($e->getMessage());
        }
    }
    
    public static function getLatestQuestions(array $where = [], int $limit = 0, int $offset = 0)
    {
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $sql = implode(" AND ", array_map(fn($attr) => "$tableName.$attr = :$attr", $attributes));

        $prepare_stmt = "
            SELECT 
                $tableName.id, $tableName.thread, $tableName.content,
                $tableName.authorID, $tableName.moduleID, $tableName.image,
                $tableName.createdAt, $tableName.updatedAt, $tableName.isActive
            FROM $tableName 
            JOIN User ur ON ur.id = $tableName.authorID 
            JOIN Module m ON m.id = $tableName.moduleID 
            WHERE 
                m.isActive = :isActive 
                AND ur.isActive = :isActive 
                AND
        ";

        $prepare_stmt = $where ? $prepare_stmt."$sql " : $prepare_stmt;

        $prepare_stmt = $prepare_stmt." ";

        $prepare_stmt .= "ORDER BY createdAt DESC";

        $prepare_stmt .= $limit ? " LIMIT :limit " : "";

        $prepare_stmt .= $offset ? " OFFSET :offset " : "";

        $statement = self::prepare($prepare_stmt);

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

    public static function countLatestQuestions (array $where = []) 
    {
        $tableName = static::tableName();

        $attributes = array_keys($where);
        $sql = implode(" AND ", array_map(fn($attr) => "$tableName.$attr = :$attr", $attributes));

        $prepare_stmt = "
            SELECT 
                COUNT('$tableName.id')
            FROM $tableName 
            JOIN User ur ON ur.id = $tableName.authorID 
            JOIN Module m ON m.id = $tableName.moduleID 
            WHERE 
                m.isActive = :isActive 
                AND ur.isActive = :isActive 
                AND
        ";

        $prepare_stm = $where ? $prepare_stmt." $sql" : $prepare_stmt;

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

    public static function search(string $query = "", array $where = [], int $limit = 0, int $offset = 0)
    {
        $tableName = static::tableName();
        $attributes = array_keys($where);

        $sql = implode(" AND ", array_map(fn($attr) => "$tableName.$attr = :$attr", $attributes));

        $prepare_stmt = "
            SELECT 
                $tableName.id, $tableName.thread, $tableName.content,
                $tableName.authorID, $tableName.moduleID, $tableName.image,
                $tableName.createdAt, $tableName.updatedAt, $tableName.isActive
            FROM $tableName 
            JOIN User ur ON ur.id = $tableName.authorID 
            JOIN Module m ON m.id = $tableName.moduleID 
            WHERE 
                (`thread` LIKE :query OR `content` LIKE :query) 
                AND m.isActive = :isActive 
                AND ur.isActive = :isActive
        ";

        $prepare_stmt = $where ? $prepare_stmt." AND $sql " : $prepare_stmt;

        $prepare_stmt .= "ORDER BY createdAt DESC, updatedAt DESC";

        $prepare_stmt .= $limit ? " LIMIT :limit " : "";

        $prepare_stmt .= $offset ? " OFFSET :offset " : "";

        $statement = self::prepare($prepare_stmt);

        foreach ($where as $key => $item) {
            $statement->bindValue(":$key", $item);
        }

        if ($limit) {
            $statement->bindParam(":limit", $limit, \PDO::PARAM_INT);
        }

        if ($offset) {
            $statement->bindValue(":offset", $offset, \PDO::PARAM_INT);
        }

        $statement->bindValue(":query", "%$query%");
        try {
            $statement->execute();            
        } catch (\PDOException $e) {
            throw new \MVC\Exceptions\InternalServerErrorException($e->getMessage());
        }

        return $statement->fetchAll();
    }
}