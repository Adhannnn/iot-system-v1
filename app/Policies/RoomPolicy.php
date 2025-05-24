<?php

namespace App\Policies;

use App\Models\Room;
use App\Models\User;

class RoomPolicy
{
    public function view(User $user, Room $room)
    {
        return $user->id === $room->user_id; // Check owner
    }

    public function export(User $user, Room $room)
    {
        return $user->id === $room->user_id; // Just the owner can export
    }

    public function delete(User $user, Room $room)
    {
        return $user->id === $room->user_id; // Just owner can delete 
    }
}
