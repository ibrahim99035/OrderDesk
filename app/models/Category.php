<?php
    class Category {
        public int $id;
        public string $name;

        public function __construct(array $row) {
            $this->id   = $row['id'];
            $this->name = $row['name'];
        }
    }
?>