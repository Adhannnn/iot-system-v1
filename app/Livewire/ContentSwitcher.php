<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Room;

class ContentSwitcher extends Component
{
    public $selected = 'room-show';
    public $roomId;

    protected $listeners = [
        'load-room' => 'loadRoom',
        'load-settings' => 'loadSettings',
        'room-added' => 'handleRoomAdded'
    ];

    public function loadRoom($roomId)
    {
        $this->selected = 'room-show';
        $this->roomId = $roomId;
        $this->dispatch('room-selected', $roomId); // Dispatch event ke RoomShow
    }

    public function loadSettings()
    {
        $this->selected = 'settings';
    }

    public function handleRoomAdded()
    {
        $this->selected = 'room-show';
        $this->roomId = Room::latest()->first()->id;
    }

    public function render()
    {
        return view('livewire.content-switcher');
    }
}