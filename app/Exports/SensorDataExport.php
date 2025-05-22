<?php

namespace App\Exports;

use App\Models\SensorData;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SensorDataExport implements FromCollection, WithHeadings
{
    protected $roomId;

    public function __construct($roomId)
    {
        $this->roomId = $roomId;
    }

    public function collection()
    {
        return SensorData::where('room_id', $this->roomId)
            ->orderBy('reading_at')
            ->get([
                'reading_at',
                'temperature',
                'humidity',
                'co',
                'air_quality',
                'dust',
                'status',
            ]);
    }

    public function headings(): array
    {
        return ['Timestamp', 'Temperature (°C)', 'Humidity (%)', 'CO (ppm)', 'Air Quality (ppm)', 'Dust (µg/m³)', 'Status'];
    }
}
