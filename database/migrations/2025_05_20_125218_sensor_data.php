<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sensor_data', function (Blueprint $table) {
            $table -> id();
            $table -> foreignId('room_id') -> constrained() -> onDelete('cascade');
            
            $table -> float('temperature') -> nullable(); // DHT22
            $table -> float('humidity') -> nullable(); // DHT22
            $table -> float('co') -> nullable(); // MQ7
            $table -> float('air_quality') -> nullable(); // MQ135
            $table -> float('dust') -> nullable(); // Dust Sensor


            // Status of the condition
            $table -> enum('status', ['normal', 'smoke', 'fire']) -> default('normal'); // Status of the sensor
            
            // Time
            $table -> timestamp('reading_at') -> useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensor_data');
    }
};
