<?php

namespace App\repositories;

use App\models\User;
use App\core\Database;
use PDO;

class UserRepository extends User
{
    public function findByEmail(string $email): array|false
    {
        return $this->where("email = '$email'")->first();
    }

    public function createUser(array $data): int|false
    {
        return $this->insert(
            ['name', 'email', 'password', 'role', 'room_id', 'extension', 'image', 'is_active'],
            [
                $data['name'],
                $data['email'],
                $data['password'],
                $data['role'],
                $data['room_id']   ?: null,
                $data['extension'] ?: null,
                $data['image']     ?: null,
                $data['is_active'],
            ]
        );
    }

    // JOIN with rooms to get room_number alongside each user
    public function allWithRoom(): array
    {
        $conn = Database::getConnection();
        $sql  = "SELECT u.*, r.room_number
                 FROM users u
                 LEFT JOIN rooms r ON u.room_id = r.id
                 ORDER BY u.created_at DESC";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findWithRoom(int $id): array|false
    {
        $conn = Database::getConnection();
        $sql  = "SELECT u.*, r.room_number
                 FROM users u
                 LEFT JOIN rooms r ON u.room_id = r.id
                 WHERE u.id = $id
                 LIMIT 1";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}