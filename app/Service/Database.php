<?php
namespace App\Service;

use Dotenv\Dotenv;
use Exception;
use PDO;

class Database {
    private static $connection;

    public static function getConnection(): PDO {
        if (!self::$connection) {
            // .envファイルの読み込み
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
            $dotenv->load();

            $host = $_ENV['DB_HOST']?: 'localhost';
            $dbname= $_ENV['DB_NAME']?: 'default_db';
            $user= $_ENV['DB_USER']?: 'default_user';
            $pass= $_ENV['DB_PASS']?: 'default_pass';

            if (!$host || !$dbname || !$user || !$pass) {
//                ここは後で絶対kaeru
                throw new Exception('Database connection parameters are missing');
            }

            $dsn = "pgsql:host=$host;dbname=$dbname";
            self::$connection = new PDO($dsn, $user, $pass);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$connection;
    }
}
?>
