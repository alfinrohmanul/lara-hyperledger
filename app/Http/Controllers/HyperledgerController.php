<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class HyperledgerController extends Controller
{   public function addHarvest(Request $request)
    {
        $harvestId = $request->input('harvest_id');
        $farmerId = $request->input('farmer_id');
        $location = $request->input('location');
        $date = $request->input('date');
        $quantity = $request->input('quantity');
    
        // Call Node.js service
        $client = new \GuzzleHttp\Client();
        $response = $client->post('http://localhost:3000/invoke', [
            'json' => [
                'function' => 'addHarvest',
                'args' => [$harvestId, $farmerId, $location, $date, $quantity]
            ]
        ]);
    
        return response()->json(json_decode($response->getBody()->getContents()));
    }
    
    public function store(Request $request)
    {
        $client = new Client();
        $response = $client->post('http://localhost:3000/invoke', [
            'json' => [
                'functionName' => 'createAsset',
                'args' => [
                    $request->input('assetId'),
                    $request->input('color'),
                    $request->input('size'),
                    $request->input('owner'),
                    $request->input('value')
                ]
            ]
        ]);

        return response()->json([
            'message' => 'Transaction submitted successfully',
            'response' => json_decode($response->getBody(), true)
        ]);
        
    }
}