<?php

namespace MVC\Models;

use MVC\Core\Application;
use MVC\Helpers\Constants;

/*
$contact_table_sql = "CREATE TABLE IF NOT EXISTS `$CONSTANTS->CONTACT_TABLE` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    emailAddress VARCHAR (255) NOT NULL,
    subject VARCHAR( 255 ) NOT NULL,
    message TEXT NOT NULL,
    createdAt DATETIME NOT NULL,
    updatedAt DATETIME NOT NULL
    );
";
*/
class Contact extends TimestampModel
{
    public int $id = 0;
    public string $emailAddress = '';
    public string $subject = '';
    public string $message = '';

    public function __construct()
    {
        parent::__construct();
        $this->extendAttributes(["emailAddress", "subject", "message"]);
    }

    public static function tableName(): string
    {
        return Constants::$CONTACT_TABLE;
    }

    public static function dbFields(): string
    {
        return implode(",", array_map(fn($attr) => "$attr", [
            "id",
            "subject",
            "message",
            "emailAddress",
            "createdAt",
            "updatedAt",
        ]));
    }

    public function labels(): array
    {
        return [
            'subject' => 'Subject',
            'message' => 'Message',
            'emailAddress' => 'Your Email',
        ];
    }

    public function rules()
    {
        return [
            'subject' => [self::RULE_REQUIRED],
            'message' => [self::RULE_REQUIRED],
            'emailAddress' => [self::RULE_REQUIRED, self::RULE_EMAIL],
        ];
    }

    public function save()
    {
        try {
            $this->setCreatedAt("now");
            $this->setUpdatedAt("now");
            return parent::save();
        } catch (\Exception $e) {
            throw new \MVC\Exceptions\InternalServerErrorException($e->getMessage());
        }
    }
}