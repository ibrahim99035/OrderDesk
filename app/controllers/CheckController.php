<?php

namespace App\controllers;

use App\core\Controller;
use App\repositories\OrderRepository;

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
        $dateFrom = $this->normalizeDate($this->get('date_from', ''));
        $dateTo   = $this->normalizeDate($this->get('date_to', ''));
        $users    = $this->orderRepo->getAllUsers();
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

    private function normalizeDate(string $date): string
    {
        if ($date === '') {
            return '';
        }

        $timestamp = strtotime($date);

        if ($timestamp === false) {
            return '';
        }

        return date('Y-m-d', $timestamp);
    }
}
