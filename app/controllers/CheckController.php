<?php

namespace App\controllers;

use App\core\Controller;
use App\core\Database;
use App\repositories\OrderRepository;
use PDO;

class CheckController extends Controller
{
    private OrderRepository $orderRepo;

    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
        $this->orderRepo = new OrderRepository();
    }

    public function index(): void
    {
        $userId   = (int) $this->get('user_id', 0);
        $dateFrom = $this->get('date_from', '');
        $dateTo   = $this->get('date_to', '');
        $users    = $this->fetchUsers();
        $results  = $this->orderRepo->checksData($dateFrom, $dateTo, $userId);

        $this->render('admin/checks', [
            'current'  => 'checks',
            'users'    => $users,
            'userId'   => $userId,
            'dateFrom' => $dateFrom,
            'dateTo'   => $dateTo,
            'results'  => $results,
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
}
