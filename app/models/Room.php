<?php
    class Room {
        public int $id;
        public string $roomNumber;

        public function __construct(array $row) {
            $this->id         = $row['id'];
            $this->roomNumber = $row['room_number'];
        }
    }
?>