<?php
    class Database {
        private static ?Database $instance = null;
        private PDO $pdo;

        private function __construct() {
            $dsn = sprintf(
                "mysql:host=%s;dbname=%s;charset=utf8mb4",
                DB_HOST,
                DB_NAME
            );

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,   // throw exceptions on error
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,         // fetch as associative array
                PDO::ATTR_EMULATE_PREPARES   => false,                    // use real prepared statements
                PDO::ATTR_PERSISTENT         => false,                    // no persistent connections
            ];

            try {
                $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                // Don't expose credentials or internal details to the browser
                error_log($e->getMessage());
                die("Database connection failed. Please try again later.");
            }
        }

        // Prevent cloning of the instance
        private function __clone() {}

        // Prevent unserializing of the instance
        public function __wakeup(): void {
            throw new \Exception("Cannot unserialize a singleton.");
        }

        public static function getInstance(): Database {
            if (self::$instance === null) {
                self::$instance = new Database();
            }
            return self::$instance;
        }

        public function getConnection(): PDO {
            return $this->pdo;
        }
    }
?>