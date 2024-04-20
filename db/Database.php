<?php

namespace MVC\Db;


use MVC\Core\Application;


class Database
{
    public \PDO $pdo;

    public function __construct($dbConfig = [])
    {
        $dbDsn = $dbConfig['dsn'] ?? '';
        $username = $dbConfig['user'] ?? '';
        $password = $dbConfig['password'] ?? '';

        try {
			$this->pdo = new \PDO($dbDsn, $username, $password);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			
		} catch (PDOException $e) {
			echo "Connected to the database failed!<br>";
			die($e->getMessage());
		}
    }

    public function prepare($sql): \PDOStatement
    {
        return $this->pdo->prepare($sql);
    }

    private function log($message)
    {
        echo "[" . date("Y-m-d H:i:s") . "] - " . $message . PHP_EOL;
    }

    public function getInsertedId(): int
    {
        return $this->pdo->lastInsertId();
    }
}