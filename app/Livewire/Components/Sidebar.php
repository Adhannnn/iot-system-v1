<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\Room;
use App\Livewire\ContentSwitcher;

class Sidebar extends Component
{
    public $rooms;
    public $currentRoomId;

    protected $listeners = ['room-added' => 'refreshRooms'];

    public function mount()
    {
        $this->refreshRooms();
    }

    public function refreshRooms()
    {
        $this->rooms = Room::all();
    }

    public function selectRoom($roomId)
    {
        $this->currentRoomId = $roomId;
        $this->dispatch('load-room', $roomId)->to(ContentSwitcher::class);
    }

    public function goToSettings()
    {
        $this->dispatch('load-settings')->to(ContentSwitcher::class);
    }

    public function render()
    {
        return view('livewire.components.sidebar');
    }
}