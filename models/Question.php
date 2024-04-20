<?php

namespace MVC\Models;

use MVC\Core\Application;
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
            $this->authorID = Application::$app->session->get('user');
            return parent::save();
        } catch (\Exception $e) {
            throw new \MVC\Exceptions\InternalServerErrorException($e->getMessage());
        }
    }
}