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

    // Static Livewire events
    protected $listeners = [
        'loadRoom' => 'loadRoom',
        'room-selected' => 'loadRoom',
        'SensorDataReceived' => 'handleReceivedData'
    ];

    // Dynamic Echo channel listener
    public function getListeners()
    {
        $baseListeners = $this->listeners;

        if ($this->roomId) {
            $baseListeners["echo:room.{$this->roomId},SensorDataReceived"] = 'handleReceivedData';
        }

        return $baseListeners;
    }

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

    public function handleReceivedData($payload)
    {
        if (!isset($payload['roomId']) || $payload['roomId'] != $this->roomId) {
            return;
        }

        $this->sensorData = [
            'dht22' => [
                'temperature' => $payload['sensorData']['dht22']['temperature'] ?? null,
                'humidity' => $payload['sensorData']['dht22']['humidity'] ?? null,
            ],
            'mq7' => [
                'co' => $payload['sensorData']['mq7']['co'] ?? null,
            ],
            'mq135' => [
                'air_quality' => $payload['sensorData']['mq135']['air_quality'] ?? null,
            ],
            'dust' => [
                'dust' => $payload['sensorData']['dust']['dust'] ?? null,
            ],
        ];

        // Trigger update di browser (Livewire.on)
        $this->dispatch('sensor-data-updated', $this->sensorData);
    }

    public function loadRoom($roomId)
    {
        $this->roomId = $roomId;
        $this->room = Room::find($roomId);
        $this->sensorData = [];

        // Notify frontend to resubscribe to new room
        $this->dispatch('subscribeToRoom', $roomId);
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
