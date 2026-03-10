<?php
namespace App\models ;
use App\core\Model ;
    class User extends  Model {
        public int $id;
        public string $name;
        public string $email;
        public string $password;
        public string $role;
        public ?int $roomId;
        public ?string $extension;
        public ?string $image;
        public bool $isActive;
        public string $createdAt;

        public function __construct() {
            parent::__construct("users");
            // $this->id        = $row['id'];
            // $this->name      = $row['name'];
            // $this->email     = $row['email'];
            // $this->password  = $row['password'];
            // $this->role      = $row['role'];
            // $this->roomId    = $row['room_id']    ?? null;
            // $this->extension = $row['extension']  ?? null;
            // $this->image     = $row['image']      ?? null;
            // $this->isActive  = (bool) $row['is_active'];
            // $this->createdAt = $row['created_at'];
        }

        public function isAdmin(): bool {
            return $this->role === 'admin';
        }
    }
?>