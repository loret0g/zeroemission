<?php
namespace App\Database;

use mysqli;

final class DBConnection
{
    private static $connection = null;

    // Constructor privado para implementar el patrón Singleton
    private function __construct() {}

    public static function getConnection()
    {
        if (self::$connection === null) {
            // Obtener las variables de entorno en tiempo de ejecución
            $host     = $_ENV['DB_HOST']     ?? 'localhost';
            $database = $_ENV['DB_NAME']     ?? 'zeroemission';
            $user     = $_ENV['DB_USER']     ?? 'root';
            $password = $_ENV['DB_PASSWORD'] ?? '';
            $port     = $_ENV['DB_PORT']     ?? 3306;

            $conn = new mysqli($host, $user, $password, $database, $port);

            if ($conn->connect_error) {
                die("Database connection failed: " . $conn->connect_error);
            }
            $conn->set_charset('utf8mb4');

            self::$connection = $conn;
        }
        return self::$connection;
    }
}