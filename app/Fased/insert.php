<?php

namespace App\Fased;

use App\core\Database;
use PDOException;

class insert
{
    public $table;
    public $columns;
    public $values;

    public function __construct($table, $columns, $values)
    {
        $this->table = $table;
        $this->columns = $columns;
        $this->values = $values;
    }

    public function insert()
    {
        $conn = Database::getConnection();

        $columns = implode(",", $this->columns);

        $placeholders = implode(
            ",",
            array_fill(0, count($this->values), "?")
        );

        $sql = "INSERT INTO {$this->table} ($columns)
                VALUES ($placeholders)";

        try {

            $stmt = $conn->prepare($sql);

            $stmt->execute($this->values);

            return $stmt->rowCount();

        } catch (PDOException $e) {

            var_dump($e->getMessage());
            return false;
        }
    }
}