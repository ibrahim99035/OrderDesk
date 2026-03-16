<?php

namespace App\controllers;

use App\core\Controller;
use App\core\Database;
use PDO;

class CheckController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
    }

    public function index(): void
    {
        $userId  = (int) $this->get('user_id', 0);
        $users   = $this->fetchUsers();
        $results = $this->fetchChecks($userId);

        $this->render('admin/checks', [
            'current' => 'checks',
            'users'   => $users,
            'userId'  => $userId,
            'results' => $results,
        ]);
    }

  
    private function fetchUsers(): array
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare(
            "SELECT id, name
             FROM users
             ORDER BY name ASC"
        );
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

 
    private function fetchChecks(int $userId = 0): array
    {
        $conn = Database::getConnection();

        $sql = "
            SELECT
                o.id,
                o.user_id,
                o.total,
                o.created_at,
                u.name AS user_name
            FROM orders o
            INNER JOIN users u ON u.id = o.user_id
        ";

        $params = [];

        if ($userId > 0) {
            $sql .= " WHERE o.user_id = :user_id";
            $params['user_id'] = $userId;
        }

        $sql .= " ORDER BY u.name ASC, o.created_at DESC";

        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $grouped = [];

        foreach ($rows as $row) {
            $uid = (int) $row['user_id'];

            if (!isset($grouped[$uid])) {
                $grouped[$uid] = [
                    'user_id'    => $uid,
                    'user_name'  => $row['user_name'],
                    'user_total' => 0,
                    'orders'     => [],
                ];
            }

            $grouped[$uid]['orders'][] = [
                'id'         => (int) $row['id'],
                'total'      => (float) $row['total'],
                'created_at' => $row['created_at'],
            ];

            $grouped[$uid]['user_total'] += (float) $row['total'];
        }

        return array_values($grouped);
    }
}
