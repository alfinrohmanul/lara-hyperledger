<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class DataController extends Controller
{
    public function store(Request $request)
    {
        // Validasi data input dari Laravel
        $validatedData = $request->validate([
            'data' => 'required|string',
        ]);

        $data = $validatedData['data'];

        // 1. Simpan Data di IPFS menggunakan Helia
        $client = new Client();
        $response = $client->post('http://127.0.0.1:5001/api/v0/add', [
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => $data,
                ],
            ],
        ]);

        $ipfsResult = json_decode($response->getBody(), true);

        if (isset($ipfsResult['Hash'])) {
            $ipfsHash = $ipfsResult['Hash'];
        } else {
            return response()->json(['error' => 'Failed to save data to IPFS'], 500);
        }

        // 2. Kirim Hash ke Hyperledger Fabric
        try {
            // Menggunakan Fabric SDK (Node.js atau PHP SDK)
            $fabricSDK = new \Hyperledger\Fabric\SDK();
            $contract = $fabricSDK->getContract('mychannel', 'mychaincode');
            $fabricResponse = $contract->submitTransaction('storeIPFSHash', $ipfsHash);

            // Simpan respons dari Hyperledger Fabric (opsional)
            $transactionId = $fabricResponse->getTransactionID();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send data to Hyperledger Fabric'], 500);
        }

        // 3. Kembalikan hasil penyimpanan ke IPFS dan transaksi Hyperledger
        return response()->json([
            'message' => 'Data successfully stored in IPFS and Hyperledger Fabric',
            'ipfs_hash' => $ipfsHash,
            'transaction_id' => $transactionId ?? 'N/A',
        ]);
    }
}