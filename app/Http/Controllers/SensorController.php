<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SensorData;

class SensorController extends Controller
{
    public function store(Request $request) {
        $data = $request -> json() -> all();

        $sensor = $data['sensorData'] ?? [];

        $status = $data['status'] ?? 'normal';

        if (!in_array($status, ['normal', 'smoke', 'fire'])) {
            $status = 'normal';
        }

        $reading = SensorData::create([
            'room_id' => $data['roomId'],
            'temperature' => $sensor['temperature'] ?? null,
            'humidity' => $sensor['humidity'] ?? null,
            'co' => $sensor['co'] ?? null,
            'air_quality' => $sensor['air_quality'] ?? null,
            'dust' => $sensor['dust'] ?? null,
            'status' => $status,
            'reading_at' => now(),
        ]);

        return response() -> json([
            'status' => 'success',
            'message' => 'Sensor data saved successfully.',
            'data' => $reading,
        ]);
    }
}
