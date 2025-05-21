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
            $table -> enum('sensor_type', [
                'temperature', // Dht 22
                'humidity', // Dht 22
                'co', // MQ 7
                'air_quality', // MQ 135
                'dust_density' // GP2Y1010AU0F
            ]);
            $table -> float('value');

            // Flag if detect smoke or fire
            $table -> boolean('is_alert') -> default(false);

            // Time
            $table -> timestamp('reading_at') -> useCurrent();

            // Index for faster query
            $table -> index(['room_id', 'sensor_type']);
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
