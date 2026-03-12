<?php

namespace App\repositories;

use App\models\Room;

class RoomRepository extends Room
{
    public function allRooms(): array
    {
        return $this->orderBy('room_number')->get();
    }
}