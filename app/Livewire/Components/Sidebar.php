<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;

class Sidebar extends Component
{
    public $rooms;
    public $currentRoomId;

    public function mount()
    {
        $this->rooms = Room::all();
    }

    public function selectRoom($roomId)
    {
        $this->currentRoomId = $roomId;
        $this->dispatch('room-selected', roomId: $roomId);
    }

    public function render()
    {
        return view('livewire.components.sidebar');
    }
}
