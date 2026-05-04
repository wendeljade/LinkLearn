<?php
$host = '127.0.0.1';
$db   = 'linklearn_db';
$user = 'root';
$pass = '';

$dsn = "mysql:host=$host;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    // 1. Find all tenant databases
    $stmt = $pdo->query("SHOW DATABASES LIKE 'linklearn_org_%'");
    $databases = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($databases as $database) {
        echo "Dropping tenant database: $database\n";
        $pdo->exec("DROP DATABASE `$database`");
    }

    // 2. Clear organizations and domains from central DB
    $pdo->exec("USE `$db`");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
    $pdo->exec("TRUNCATE TABLE domains;");
    $pdo->exec("TRUNCATE TABLE organizations;");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");
    echo "Cleared domains and organizations from central database.\n";

    // 3. Reset users (unlink organizations and make non-superadmins into students)
    $pdo->exec("UPDATE users SET organization_id = NULL;");
    $pdo->exec("UPDATE users SET role = 'student' WHERE email != 'admin@example.com';");
    $pdo->exec("UPDATE users SET role = 'super_admin' WHERE email = 'admin@example.com';");
    echo "Reset users.\n";
    
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
