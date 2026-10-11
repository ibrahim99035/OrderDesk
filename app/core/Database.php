<?php

namespace App\core;

use PDO;
use PDOException;

class Database
{
    private $host;
    private $username;
    private $password;
    private $db_name;
    public $conn;
    private static $instance = null;

    private function __construct()
    {
        $this->host = getenv('DB_HOST');
        $this->username = getenv('DB_USER');
        $this->password = getenv('DB_PASS');
        $this->db_name = getenv('DB_NAME');

        $this->connect();
    }

    private function connect()
    {
        $mysql = $this->tryMySQL();
        if ($mysql !== null) {
            $this->conn = $mysql;
            return;
        }

        // SQLite fallback so the project runs locally without a MySQL server.
        $this->db_name = $this->db_name ?: 'orderdesk.sqlite';
        $this->conn = new PDO("sqlite:$this->db_name");
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->createSqliteSchema();
    }

    private function tryMySQL()
    {
        if (!$this->host || !$this->username || !$this->password || !$this->db_name) {
            return null;
        }

        try {
            $conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4",
                $this->username,
                $this->password
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            return null;
        }
    }

    private function createSqliteSchema()
    {
        $schemaPath = __DIR__ . '/../../schema.sqlite';
        if (!file_exists($schemaPath)) {
            $schemaPath = __DIR__ . '/../../schema.sql';
        }
        if (!file_exists($schemaPath)) {
            return;
        }

        // Only create tables when the database is empty.
        $count = $this->conn->query(
            "SELECT COUNT(*) FROM sqlite_master WHERE type='table'"
        )->fetchColumn();
        if ((int) $count > 0) {
            return;
        }

        $schema = file_get_contents($schemaPath);
        foreach (explode(';', trim($schema)) as $stmt) {
            $stmt = trim($stmt);
            if ($stmt !== '') {
                $this->conn->exec($stmt);
            }
        }
    }

    public static function getConnection()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }

        return self::$instance->conn;
    }
}
