<?php

namespace App\repositories;

use App\core\Database;
use PDO;

class OrderRepository
{
    // Get the latest order for a specific user
    public function latestByUser(int $userId): ?array
    {
        $conn = Database::getConnection();
        $sql  = "SELECT o.*, r.room_number 
                 FROM orders o
                 LEFT JOIN rooms r ON o.room_id = r.id
                 WHERE o.user_id = :user_id
                 ORDER BY o.created_at DESC
                 LIMIT 1";

        $stmt = $conn->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}