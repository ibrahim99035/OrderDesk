<?php
    class OrderItem {
        public int $id;
        public int $orderId;
        public int $productId;
        public int $quantity;
        public float $unitPrice;

        //Joined fields for display
        public ?string $productName;
        public ?string $productImage;

        public function __construct(array $row) {
            $this->id           = $row['id'];
            $this->orderId      = $row['order_id'];
            $this->productId    = $row['product_id'];
            $this->quantity     = (int)   $row['quantity'];
            $this->unitPrice    = (float) $row['unit_price'];

            // Optional joined fields
            $this->productName  = $row['product_name']  ?? null;
            $this->productImage = $row['product_image'] ?? null;
        }

        public function subtotal(): float {
            return $this->quantity * $this->unitPrice;
        }
    }
?>