<?php
$pdo = new PDO('mysql:host=127.0.0.1', 'root', '');
$databases = $pdo->query('SHOW DATABASES LIKE "linklearn_org_%"')->fetchAll(PDO::FETCH_COLUMN);
foreach ($databases as $db) {
    try {
        $stmt = $pdo->query("SELECT title FROM `$db`.files");
        if ($stmt) {
            foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $t) {
                echo "$db: $t\n";
            }
        }
    } catch (Exception $e) {}
}
