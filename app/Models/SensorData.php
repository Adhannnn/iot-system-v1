<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SensorData extends Model
{
    
    protected $fillable = ['room_id', 'sensor_type', 'value', 'is_alert'];

    public $timestamps = false;

    public function Room(): BelongsTo {
        return $this -> belongsTo(Room::class);
    }

    
}
