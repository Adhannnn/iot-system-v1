<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\Room;


class RoomShow extends Component
{
    public $room;
    public $roomId;
    public $name;
    public $sensorData = [];

    protected $rules = [
        'name' => 'required|string|unique:rooms,name'
    ];

    protected $listeners = [
        'SensorDataReceived' => 'handleReceivedData',
        'loadRoom' => 'loadRoom',
        'room-selected' => 'loadRoom'
    ];

    public function mount($roomId = null)
    {
        if ($roomId) {
            $this->loadRoom($roomId);
        } else {
            $firstRoom = Room::first();
            if ($firstRoom) {
                $this->loadRoom($firstRoom->id);
            }
        }
    }
    public function handleReceivedData($sensorData)
    {

        $this->sensorData = $sensorData;
    }

    public function loadRoom($roomId)
    {
        $this->roomId = $roomId;
        $this->room = Room::find($roomId);
        $this->sensorData = [];

        $this->dispatch('subscribeToRoomChannel', ['roomId' => $roomId]);
    }

    public function addRoom()
    {
        $this->validate();

        Room::create(['name' => $this->name]);

        $this->reset('name');

        $this->dispatch('room-added-success');
    }

    public function render()
    {
        return view('livewire.components.room-show');
    }
}
