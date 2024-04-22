<?php
use MVC\Helpers\Constants;
use MVC\Db\Database;
use MVC\Core\DotEnv;
use MVC\Helpers\Common;
use MVC\Helpers\Permissions;


require_once __DIR__.'/../autoload.php';
(new DotEnv(__DIR__ . '/../.env'))->load();

$config = [
    'db' => [
        'dsn' => getenv('DB_DSN'),
        'user' => getenv('DB_USER'),
        'password' => getenv('DB_PASSWORD'),
    ]
];


$db = new Database($config['db']);
$db = $db->pdo;

function getAllPermissions() {
    $permissions = new \ReflectionClass('\MVC\Helpers\Permissions');
    $staticProperties = $permissions->getStaticProperties();
    return $staticProperties;
}

// execute SQL statements
echo "<br>Start Initializing Permissions Records!<br>";
foreach (getAllPermissions() as $propertyName => $value) { 
    $now = new \DateTime("now");
    $now = $now->format('Y-m-d\TH:i:s');
    $sql = "INSERT INTO `Permission` (`perm`, `isActive`, `createdAt`, `updatedAt`) VALUES ('$value', '1', '$now', '$now') ON DUPLICATE KEY UPDATE
    perm = '$value', isActive = '1', createdAt = '$now', updatedAt = '$now';
    ";
    $statement = $db->prepare($sql);
    var_dump($statement);
    $statement->execute();
}
echo "<br>Initializing Permissions Records progress has completed!<br>";
?>