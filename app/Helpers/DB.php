<?php
namespace App\Helpers;

class DB {
    public static function get() {
        static $pdo = null;
        if ($pdo === null) {
            $host = getenv('DB_HOST') ?: '127.0.0.1';
            $db   = getenv('DB_NAME') ?: 'ecommerce';
            $user = getenv('DB_USER') ?: 'root';
            $pass = getenv('DB_PASS') ?: '123456';
            $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
            try {
              $pdo = new \PDO($dsn, $user, $pass, [\PDO::ATTR_ERRMODE=>\PDO::ERRMODE_EXCEPTION]);
            } catch(\Exception $e) {
              die('DB connection failed: ' . $e->getMessage());
            }
        }
        return $pdo;
    }
}
