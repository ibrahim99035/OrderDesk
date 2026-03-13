<?php

namespace App\repositories;

use App\models\User;
use App\core\Database;
use PDO;

class UserRepository extends User
{
    /**
     * Find a user by email using a prepared statement (no SQL injection).
     */
    public function findByEmail(string $email): array|false
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new user. Returns the new row's ID or false on failure.
     */
    public function createUser(array $data): int|false
    {
        return $this->insert(
            ['name', 'email', 'password', 'role', 'room_id', 'extension', 'image', 'is_active'],
            [
                $data['name'],
                $data['email'],
                $data['password'],
                $data['role']      ?? 'user',
                $data['room_id']   ?: null,
                $data['extension'] ?: null,
                $data['image']     ?: null,
                $data['is_active'] ?? 1,
            ]
        );
    }

    /**
     * Update an existing user.
     * Password and image are only updated when provided.
     */
    public function updateUser(int $id, array $data): int|false
    {
        $columns = ['name', 'email', 'role', 'room_id', 'extension', 'is_active'];
        $values  = [
            $data['name'],
            $data['email'],
            $data['role']      ?? 'user',
            $data['room_id']   ?: null,
            $data['extension'] ?: null,
            $data['is_active'] ?? 1,
        ];

        if (!empty($data['password'])) {
            $columns[] = 'password';
            $values[]  = $data['password'];
        }

        if (!empty($data['image'])) {
            $columns[] = 'image';
            $values[]  = $data['image'];
        }

        $values[] = $id; // for WHERE clause

        $set = implode(', ', array_map(fn($c) => "$c = ?", $columns));
        $sql = "UPDATE users SET $set WHERE id = ?";

        $conn = Database::getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute($values);
        return $stmt->rowCount();
    }

    /**
     * All users joined with their room number.
     */
    public function allWithRoom(): array
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare(
            "SELECT u.*, r.room_number
             FROM users u
             LEFT JOIN rooms r ON u.room_id = r.id
             ORDER BY u.created_at DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Single user joined with room number.
     */
    public function findWithRoom(int $id): array|false
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare(
            "SELECT u.*, r.room_number
             FROM users u
             LEFT JOIN rooms r ON u.room_id = r.id
             WHERE u.id = ?
             LIMIT 1"
        );
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}