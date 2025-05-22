<?php

namespace App\Policies;

use App\Models\Room;
use App\Models\User;

class RoomPolicy
{
    public function view(User $user, Room $room)
    {
        return $user->id === $room->user_id; // Contoh cek owner
    }

    public function export(User $user, Room $room)
    {
        return $user->id === $room->user_id; // hanya owner boleh export
    }

    public function delete(User $user, Room $room)
    {
        return $user->id === $room->user_id; // hanya owner boleh delete
    }
}
