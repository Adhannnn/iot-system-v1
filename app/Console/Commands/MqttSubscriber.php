<?php

namespace App\Console\Commands;

use App\Events\SensorDataReceived;
use Illuminate\Console\Command;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\MqttClient;
use App\Models\SensorData;
use Illuminate\Support\Carbon;

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

        $mqtt->subscribe("IoT/group8", function ($topic, $message) {
            try {
                $this->info("📩 Message on {$topic}: {$message}");
                $payload = json_decode($message, true);

                if (!$payload) {
                    $this->error("❌ JSON decode failed");
                    return;
                }

                if (isset($payload['room_id'], $payload['sensorData'])) {
                    $roomId = $payload['room_id'];
                    $sensorData = $payload['sensorData'];
                    $status = $payload['status'] ?? 'normal';

                    $this->info("Saving sensor data for room {$roomId}...");
                    SensorData::create([
                        'room_id' => $roomId,
                        'temperature' => $sensorData['dht22']['temperature'] ?? null,
                        'humidity' => $sensorData['dht22']['humidity'] ?? null,
                        'co' => $sensorData['mq7']['co'] ?? null,
                        'air_quality' => $sensorData['mq135']['air_quality'] ?? null,
                        'dust' => $sensorData['dust']['dust'] ?? null,
                        'status' => $status,
                        'reading_at' => now(),
                    ]);
                    $this->info("Saved successfully.");

                    event(new SensorDataReceived($roomId, $sensorData, $status));
                    $this->info("Broadcasted event for room {$roomId}.");
                } else {
                    $this->error("❌ Invalid payload structure");
                }
            } catch (\Throwable $e) {
                $this->error("❌ Exception: " . $e->getMessage());
            }
        }, 0);

        $mqtt->loop(true);

        $mqtt->disconnect();
    }
}
