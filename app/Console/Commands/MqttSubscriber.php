<?php

namespace App\Console\Commands;

use App\Events\SensorDataReceived;
use Illuminate\Console\Command;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\MqttClient;

class MqttSubscriber extends Command
{
    protected $signature = 'mqtt:subscribe';
    protected $description = 'Subscribe to MQTT topic and broadcast data to frontend';

    public function handle()
    {
        $server = "broker.emqx.io";
        $port = 1883;
        $clientId = 'laravel-mqtt-' . uniqid();

        $connectionSettings = (new ConnectionSettings)
            ->setKeepAliveInterval(60)
            ->setLastWillTopic('last/Will')
            ->setLastWillMessage('Client Disconnected')
            ->setLastWillQualityOfService(0);

        $mqtt = new MqttClient($server, $port, $clientId);
        $mqtt->connect($connectionSettings, true);

        $this->info("✅ Connected to MQTT Broker at {$server}:{$port}");

        $mqtt->subscribe("room/1/sensor", function ($topic, $message) {
            $this->info("📩 Message on {$topic}: {$message}");

            $payload = json_decode($message, true);

            if ($payload && isset($payload['room_id']) && isset($payload['sensorData'])) {
                $roomId = $payload['room_id'];
                $sensorData = $payload['sensorData'];

                // 🚀 Broadcast ke frontend
                event(new SensorDataReceived($roomId, $sensorData));

                $this->info("📡 Broadcasted SensorDataReceived for room {$roomId}");
            } else {
                $this->error("❌ Invalid payload: {$message}");
            }
        }, 0);

        $mqtt->loop(true);

        $mqtt->disconnect();
    }
}
