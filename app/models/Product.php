<?php
    class Product {
        public int $id;
        public string $name;
        public float $price;
        public ?int $categoryId;
        public ?string $categoryName;
        public ?string $image;
        public bool $isAvailable;
        public string $createdAt;

        public function __construct(array $row) {
            $this->id           = $row['id'];
            $this->name         = $row['name'];
            $this->price        = (float) $row['price'];
            $this->categoryId   = $row['category_id']   ?? null;
            $this->categoryName = $row['category_name'] ?? null;
            $this->image        = $row['image']         ?? null;
            $this->isAvailable  = (bool) $row['is_available'];
            $this->createdAt    = $row['created_at'];
        }
    }
?>