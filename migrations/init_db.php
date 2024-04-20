<?php
use MVC\Helpers\Constants;
use MVC\Db\Database;
use MVC\Core\DotEnv;

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
$sql_statements = [];

$contact_table_sql = "CREATE TABLE IF NOT EXISTS ".Constants::$CONTACT_TABLE." (
    id INT AUTO_INCREMENT PRIMARY KEY,
    emailAddress VARCHAR (255) NOT NULL,
    subject VARCHAR( 255 ) NOT NULL,
    message TEXT NOT NULL,
    createdAt DATETIME NOT NULL,
    updatedAt DATETIME NOT NULL
    );
";
array_push($sql_statements, $contact_table_sql);

$user_table_sql = "CREATE TABLE IF NOT EXISTS ".Constants::$USER_TABLE." (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR( 255 ) NOT NULL,
    lastName VARCHAR( 255 ) NOT NULL,
    emailAddress VARCHAR (255) NOT NULL,
    password VARCHAR (2000) NOT NULL,
    isActive BOOLEAN NOT NULL,
    isSuperAdmin BOOLEAN NOT NULL,
    registeredAt DATETIME NOT NULL,
    reputation INT NOT NULL,
    birthday DATE NULL,
    aboutMe TEXT (1500),
    loginedAt DATETIME NULL
    );
";
array_push($sql_statements, $user_table_sql);

$role_table_sql = "CREATE TABLE IF NOT EXISTS ".Constants::$ROLE_TABLE." (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR( 255 ) NOT NULL,
    isActive BOOLEAN NOT NULL,
    createdAt DATETIME NOT NULL,
    updatedAt DATETIME NOT NULL
    );
";
array_push($sql_statements, $role_table_sql);

$user_role_table_sql = "CREATE TABLE IF NOT EXISTS ".Constants::$USER_ROLE_TABLE." (
    id INT AUTO_INCREMENT PRIMARY KEY,
    userID INT NOT NULL,
    CONSTRAINT fkUser
        FOREIGN KEY(userID) 
        REFERENCES User(id) ON DELETE CASCADE,
    roleID INT NOT NULL,
    CONSTRAINT fkUserRole
        FOREIGN KEY(roleID) 
        REFERENCES Role(id) ON DELETE CASCADE
    );
";
array_push($sql_statements, $user_role_table_sql);

$permission_table_sql = "CREATE TABLE IF NOT EXISTS ".Constants::$PERMISSION_TABLE." (
    id INT AUTO_INCREMENT PRIMARY KEY,
    perm VARCHAR( 255 ) NOT NULL,
    isActive BOOLEAN NOT NULL,
    createdAt DATETIME NOT NULL,
    updatedAt DATETIME NOT NULL
    );
";
array_push($sql_statements, $permission_table_sql);

$role_permission_table_sql = "CREATE TABLE IF NOT EXISTS ".Constants::$ROLE_PERMISSION_TABLE." (
    id INT AUTO_INCREMENT PRIMARY KEY,
    roleID INT NOT NULL,
    CONSTRAINT fkPermissionRole
        FOREIGN KEY(roleID) 
        REFERENCES Role(id) ON DELETE CASCADE,
    permissionID INT NOT NULL,
    CONSTRAINT fkPermission
        FOREIGN KEY(permissionID) 
        REFERENCES Permission(id) ON DELETE CASCADE
    );
";
array_push($sql_statements, $role_permission_table_sql);

$module_table_sql = "CREATE TABLE IF NOT EXISTS ".Constants::$MODULE_TABLE." (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR( 255 ) NOT NULL,
    isActive BOOLEAN NOT NULL,
    createdAt DATETIME NOT NULL,
    updatedAt DATETIME NOT NULL
    );
";
array_push($sql_statements, $module_table_sql);

$question_table_sql = "CREATE TABLE IF NOT EXISTS ".Constants::$QUESTION_TABLE." (
    id INT AUTO_INCREMENT PRIMARY KEY,
    thread VARCHAR( 255 ) NOT NULL,
    content TEXT NOT NULL,
    image VARCHAR (5028) NULL,
    createdAt DATETIME NOT NULL,
    updatedAt DATETIME NOT NULL,
    isActive BOOLEAN NOT NULL,
    authorID INT NOT NULL,
    CONSTRAINT fkQuestionAuthor
        FOREIGN KEY(authorID) 
        REFERENCES User(id) ON DELETE CASCADE,
    moduleID INT NOT NULL,
    CONSTRAINT fkQuestionModule
        FOREIGN KEY(moduleID) 
        REFERENCES Module(id) ON DELETE CASCADE
    );
";
array_push($sql_statements, $question_table_sql);

$answer_table_sql = "CREATE TABLE IF NOT EXISTS ".Constants::$ANSWER_TABLE." (
    id INT AUTO_INCREMENT PRIMARY KEY,
    answer TEXT NOT NULL,
    image VARCHAR (5028) NULL,
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
array_push($sql_statements, $answer_table_sql);

// execute SQL statements
echo "<br>Start DB migration progress!<br>";
foreach ($sql_statements as $sql) {
    try {
        $statement = $db->prepare($sql);
        $statement->execute();
        print("\n$sql Excuted.\n");
    } catch(PDOException $e) {
        print("\nCannot execute $sql.\n");
        echo $e->getMessage();//Remove or change message in production code
    }
}
echo "<br>DB migration progress has completed!<br>";
?>