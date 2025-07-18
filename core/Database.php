<?php
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

class Database {
    private static $conexion = null;

    public static function getConexion() {
        if (self::$conexion === null) {
            $host = $_ENV['DB_HOST'] ?? 'localhost';
            $user = $_ENV['DB_USER'] ?? 'postgres';
            $pass = $_ENV['DB_PASS'] ?? '';
            $name = $_ENV['DB_NAME'] ?? '';
            $conn_string = "host=$host dbname=$name user=$user password=$pass";
            self::$conexion = pg_connect($conn_string);
            if (!self::$conexion) {
                die('Error de conexión a PostgreSQL');
            }
        }
        return self::$conexion;
    }
}