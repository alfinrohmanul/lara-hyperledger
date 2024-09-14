<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class IPFSController extends Controller
{
    public function storeToIPFS(Request $request)
    {
        // Validasi data input
        $validatedData = $request->validate([
            'data' => 'required|string',
        ]);

        $data = $validatedData['data'];

        try {
            // Buat client HTTP untuk berkomunikasi dengan IPFS Helia
            $client = new Client();
            
            // Kirim data ke IPFS Helia
            $response = $client->post('http://127.0.0.1:5001/api/v0/add', [
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => $data,
                    ],
                ],
            ]);

            // Decode respons JSON dari IPFS
            $ipfsResult = json_decode($response->getBody(), true);

            // Cek apakah ada hash yang diterima dari IPFS
            if (isset($ipfsResult['Hash'])) {
                $ipfsHash = $ipfsResult['Hash'];

                // Kembalikan hash sebagai respons API
                return response()->json([
                    'message' => 'Data berhasil disimpan di IPFS',
                    'ipfs_hash' => $ipfsHash,
                ], 200);
            } else {
                return response()->json(['error' => 'Gagal menyimpan data ke IPFS'], 500);
            }

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
