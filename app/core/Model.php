<?php

namespace App\core;

use App\core\Database;
use App\Fased\insert ;
use PDO;
use PDOException;

class Model
{
    public $table;

    private ?string $condition = null;
    protected ?string $groupBy = null;
    protected ?string $orderBy = null;
    protected ?int $limit = null;

    public function __construct($table)
    {
        $this->table = $table;
    }

    public function insert($columns, $values)
    {
        $insert = new Insert($this->table, $columns, $values);
        return $insert->insert();
    }

    public function where($condition)
    {
        $this->condition = $condition;
        return $this;
    }

    public function orWhere($condition)
    {
        if (!$this->condition) {
            $this->condition = $condition;
        } else {
            $this->condition .= " OR $condition";
        }

        return $this;
    }

    public function andWhere($condition)
    {
        if (!$this->condition) {
            $this->condition = $condition;
        } else {
            $this->condition .= " AND $condition";
        }

        return $this;
    }

    public function delete()
    {
        $conn = Database::getConnection();

        $sql = "DELETE FROM $this->table WHERE $this->condition";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function all()
    {
        $conn = Database::getConnection();

        $sql = "SELECT * FROM $this->table";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return $e;
        }
    }

    public function update($columns_values)
    {
        $conn = Database::getConnection();

        $sql = "UPDATE $this->table SET $columns_values WHERE $this->condition";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            return $stmt->rowCount();

        } catch (PDOException $e) {
            return false;
        }
    }

    public function find($id)
    {
        $conn = Database::getConnection();

        $sql = "SELECT * FROM $this->table WHERE id = $id";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public function limit($number)
    {
        $this->limit = $number;
        return $this;
    }

    public function groupBy($column)
    {
        $this->groupBy = $column;
        return $this;
    }

    public function orderBy($column, $order = "ASC")
    {
        $this->orderBy = "$column $order";
        return $this;
    }

    public function get()
    {
        $conn = Database::getConnection();

        $sql = "SELECT * FROM $this->table";

        if ($this->condition) {
            $sql .= " WHERE $this->condition";
        }

        if ($this->groupBy) {
            $sql .= " GROUP BY $this->groupBy";
        }

        if ($this->orderBy) {
            $sql .= " ORDER BY $this->orderBy";
        }

        if ($this->limit) {
            $sql .= " LIMIT $this->limit";
        }

        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public function first()
    {
        $conn = Database::getConnection();

        $sql = "SELECT * FROM $this->table";

        if ($this->condition) {
            $sql .= " WHERE $this->condition";
        }

        if ($this->groupBy) {
            $sql .= " GROUP BY $this->groupBy";
        }

        if ($this->orderBy) {
            $sql .= " ORDER BY $this->orderBy";
        }

        $sql .= " LIMIT 1";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public function count()
    {
        $conn = Database::getConnection();

        $sql = "SELECT COUNT(*) as count FROM $this->table";

        if ($this->condition) {
            $sql .= " WHERE $this->condition";
        }

        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC)['count'];

        } catch (PDOException $e) {
            return false;
        }
    }

    public function paginate($perPage, $page)
    {
        $conn = Database::getConnection();

        $sql = "SELECT * FROM $this->table";

        if ($this->condition) {
            $sql .= " WHERE $this->condition";
        }

        if ($this->orderBy) {
            $sql .= " ORDER BY $this->orderBy";
        }

        $offset = ($page - 1) * $perPage;

        $sql .= " LIMIT $perPage OFFSET $offset";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    public function paginateLinks($perPage, $page)
    {
        $total = $this->count();

        $totalPages = ceil($total / $perPage);

        $links = "";

        for ($i = 1; $i <= $totalPages; $i++) {

            if ($i == $page) {
                $links .= "<a href='?page=$i' class='active'>$i</a>";
            } else {
                $links .= "<a href='?page=$i'>$i</a>";
            }
        }

        return $links;
    }

    public function __destruct()
    {
        $this->condition = null;
        $this->groupBy = null;
        $this->orderBy = null;
        $this->limit = null;
    }
}