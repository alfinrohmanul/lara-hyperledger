<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SensorData;

class SensorDataController extends Controller
{
    public function store(Request $request)
    {

        // Validasi data
        $validatedData = $request->validate([
            'sensor_id' => 'required|string',
            'temperature' => 'required|numeric',
            'humidity' => 'required|numeric',
            'timestamp' => 'required|date',
        ]);

        // Simpan data ke database atau lakukan tindakan lain
        // Contoh: menampilkan data yang diterima
        return response()->json([
            'message' => 'Data received successfully',
            'data' => $validatedData
        
        ]);

    }
    
    public function index() 
    {
        $sensordata = SensorData::all();
        return view('sensordata', compact('sensordata'));     
    }
}