<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SensorDataReceived implements ShouldBroadcast {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */

    public $sensorData;
    public $roomId;

    public function __construct(int $roomId, array $sensorData)
    {
        $this -> roomId = $roomId;
        $this -> sensorData = $sensorData;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return new Channel('room.' . $this -> roomId);
    }

    public function broadcastAs() {
        return 'SensorDataReceived';
    }

    public function broadcastWith() {
        return [
            'roomId' => $this -> roomId,
            'sensorData' => $this -> sensorData
        ];
    }
}
