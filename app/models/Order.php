<?php
    class Order {
        public int $id;
        public int $userId;
        public int $placedBy;
        public int $roomId;
        public ?string $notes;
        public string $status;
        public float $total;
        public string $createdAt;
        public ?string $updatedAt;

        //Joined fields for display
        public ?string $userName;
        public ?string $roomNumber;
        public ?string $userExtension;

        public array $items = [];

        public function __construct(array $row) {
            $this->id            = $row['id'];
            $this->userId        = $row['user_id'];
            $this->placedBy      = $row['placed_by'];
            $this->roomId        = $row['room_id'];
            $this->notes         = $row['notes']?? null;
            $this->status        = $row['status'];
            $this->total         = (float) $row['total'];
            $this->createdAt     = $row['created_at'];
            $this->updatedAt     = $row['updated_at']?? null;

            // Optional joined fields
            $this->userName      = $row['user_name']?? null;
            $this->roomNumber    = $row['room_number']?? null;
            $this->userExtension = $row['user_extension']?? null;
        }

        public function isCancellable(): bool {
            return $this->status === 'processing';
        }

        public function isDeliverable(): bool {
            return $this->status === 'processing';
        }
    }
?>