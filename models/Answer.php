<?php

namespace MVC\Models;

use MVC\Helpers\Constants;

/*
$answer_table_sql = "CREATE TABLE IF NOT EXISTS ".Constants::$ANSWER_TABLE." (
    id INT AUTO_INCREMENT PRIMARY KEY,
    answer TEXT NOT NULL,
    createdAt DATETIME NOT NULL,
    updatedAt DATETIME NOT NULL,
    isActive BOOLEAN NOT NULL,
    questionID INT NOT NULL,
    CONSTRAINT fkQuestionAnswer
        FOREIGN KEY(questionID) 
        REFERENCES Question(id) ON DELETE CASCADE,
    authorID INT NOT NULL,
    CONSTRAINT fkAuthorAnswer
        FOREIGN KEY(authorID) 
        REFERENCES User(id) ON DELETE CASCADE
    );
";
*/
class Answer extends TimestampModel
{
    public int $id = 0;
    public string $answer = '';
    public int $isActive = self::BOOL_FALSE;
    public int $authorID = 0;
    public int $questionID = 0;

    public function __construct() {
        parent::__construct();
        $this->extendAttributes([
            "id",
            "answer",
            "isActive",
            "authorID",
            "questionID"
        ]);
    }

    public static function tableName(): string
    {
        return Constants::$ANSWER_TABLE;
    }

    public static function dbFields(): string
    {
        return implode(",", array_map(fn($attr) => "$attr", [
            "id",
            "answer",
            "isActive",
            "createdAt",
            "updatedAt",
            "authorID",
            "questionID",
        ]));
    }

    public function labels(): array
    {
        return [
            'answer' => 'Answer',
        ];
    }

    public function rules()
    {
        return [
            'answer' => [self::RULE_REQUIRED],
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

    public static function getLatestAnswers (array $where = [], int $limit = 0, int $offset = 0)
    {
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $sql = implode(" AND ", array_map(fn($attr) => "$tableName.$attr = :$attr", $attributes));

        $prepare_stmt = "
            SELECT 
                $tableName.id, $tableName.answer, $tableName.authorID, 
                $tableName.questionID, $tableName.createdAt, $tableName.updatedAt, $tableName.isActive
            FROM $tableName 
            JOIN User ur ON ur.id = $tableName.authorID 
            JOIN Question q ON q.id = $tableName.questionID 
            WHERE 
                q.isActive = :isActive 
                AND ur.isActive = :isActive 
                AND
        ";

        $prepare_stm = $where ? $prepare_stmt." $sql "."ORDER BY createdAt DESC" : $prepare_stmt." ORDER BY createdAt DESC";

        $prepare_stm .= $limit ? " LIMIT :limit " : "";

        $prepare_stm .= $offset ? " OFFSET :offset " : "";

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

    public static function countLatestAnswers (array $where = []) 
    {
        $tableName = static::tableName();

        $attributes = array_keys($where);
        $sql = implode(" AND ", array_map(fn($attr) => "$tableName.$attr = :$attr", $attributes));

        $prepare_stmt = "
            SELECT 
                COUNT('$tableName.id')
            FROM $tableName 
            JOIN User ur ON ur.id = $tableName.authorID 
            JOIN Question q ON q.id = $tableName.questionID 
            WHERE 
                q.isActive = :isActive 
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
}