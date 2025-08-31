<?php
require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../', 'settings.env');
$dotenv->load();

$h = $_ENV['MAIL_SERVERSV2'] ?? null;
$d = $_ENV['WSECMYSQL2'] ?? null;
$u = $_ENV['WSECMYSQLNAME'] ?? null;
$p = $_ENV['WSECMYSQLPASSWD'] ?? null;

if (!$h || !$d || !$u || $p === null) {
    error_log('Database configuration is missing required environment variables.');
    exit('Database configuration error.');
}

try {
    $pdo2 = new PDO(
        "mysql:host={$h};dbname={$d};charset=utf8mb4",
        $u,
        $p,
        [
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4'",
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    error_log('Database connection failed: ' . $e->getMessage());
    exit('Database connection error.');
}

function get_db_connection2() {
    global $pdo2;
    return $pdo2;
}
?>