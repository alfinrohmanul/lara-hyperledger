<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorData extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak mengikuti konvensi
    protected $table = 'sensor_data';

    // Tentukan kolom yang boleh diisi
    protected $fillable = [
        'sensor_id',
        'temperature',
        'humidity',
        'timestamp'
    ];
}