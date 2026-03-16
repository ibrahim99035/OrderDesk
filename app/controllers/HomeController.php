<?php

namespace App\controllers;

use App\core\View;
use App\core\Session;
use App\repositories\ProductRepository;
use App\repositories\RoomRepository;
use App\repositories\OrderRepository;
use App\repositories\UserRepository;

class HomeController
{
    private ProductRepository $productRepo;
    private RoomRepository $roomRepo;
    private OrderRepository $orderRepo;
    private UserRepository $userRepo;

    public function __construct()
    {
        $this->productRepo = new ProductRepository();
        $this->roomRepo    = new RoomRepository();
        $this->orderRepo   = new OrderRepository();
        $this->userRepo    = new UserRepository();
    }

    // GET /home  (user)
    public function user(): void
    {
        Session::start();
        if (!Session::isLoggedIn()) {
            header('Location: /login');
            exit;
        }

        $userId = (int) Session::get('user_id');

        $latestOrder = $this->orderRepo->latestByUser($userId);

        View::make('user.home', [
            'products'    => $this->productRepo->allAvailable() ?: [],
            'rooms'       => $this->roomRepo->allRooms() ?: [],
            'latestOrder' => $latestOrder ?: null,
            "current" => "home"
        ]);
    }

    // GET /admin/home  (admin)
    public function admin(): void
    {
        Session::start();
        if (!Session::isLoggedIn()) {
            header('Location: /login');
            exit;
        }
        if (Session::get('role') !== 'admin') {
            header('Location: /home');
            exit;
        }

        $userId = (int) Session::get('user_id');

        $latestOrder = $this->orderRepo->latestByUser($userId);

        View::make('admin.home', [
            'products'    => $this->productRepo->allAvailable() ?: [],
            'rooms'       => $this->roomRepo->allRooms() ?: [],
            'latestOrder' => $latestOrder ?: null,
            'users'       => $this->userRepo->allWithRoom() ?: [],
             "current" => "home"
        ]);
    }
}
