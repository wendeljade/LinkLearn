<?php
$pdo = new PDO('mysql:host=127.0.0.1', 'root', '');
$databases = $pdo->query('SHOW DATABASES LIKE "linklearn_org_%"')->fetchAll(PDO::FETCH_COLUMN);
foreach ($databases as $db) {
    echo "Dropping $db\n";
    $pdo->exec("DROP DATABASE `$db`");
}
$pdo->exec("DROP DATABASE IF EXISTS linklearn_db");
$pdo->exec("CREATE DATABASE linklearn_db");
echo "Done.\n";
