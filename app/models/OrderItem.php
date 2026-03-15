<?php

namespace App\models;
use App\core\Model;

class OrderItem extends Model {
    public function __construct() {
        parent::__construct("order_items");
    }
}