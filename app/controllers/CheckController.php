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
        $users   = $this->fetchUsers();
        $results = $this->fetchChecks();

        $this->render('admin/checks', [
            'current' => 'checks',
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

 
    private function fetchChecks(): array
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
            ORDER BY u.name ASC, o.created_at DESC
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
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
