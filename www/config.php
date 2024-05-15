<?php
$host = 'postgres';
$db   = 'test_db';
$user = 'test_user';
$pass = 'test_pass';
$charset = 'utf8';
$dsn = "pgsql:host=$host;port=5432;dbname=$db;user=$user;password=$pass";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>
