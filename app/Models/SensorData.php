<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SensorData extends Model
{
    
    protected $fillable = [
        'room_id',
        'temperature',
        'humidity',
        'co',
        'air_quality',
        'dust',
        'status',
        'reading_at'
    ];

    public $timestamps = false;
    
    public function Room(): BelongsTo {
        return $this -> belongsTo(Room::class);
    }
}
