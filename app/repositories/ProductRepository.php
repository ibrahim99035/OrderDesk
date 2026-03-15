<?php

namespace App\repositories;

use App\models\Product;

class ProductRepository extends Product
{
    // Get all available products
    public function allAvailable(): array
    {
        return $this->where('is_available = 1')->orderBy('name')->get();
    }

    // Get all products (for admin)
    public function allProducts(): array
    {
        return $this->orderBy('name')->get();
    }
}