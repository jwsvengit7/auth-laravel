<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Client;

class BvnValidController extends Controller
{
    public function bvn(Request $request)
    {
      
        $apiEndpoint = 'https://api.youverify.co/api/v1/kyc/verifications/bvn';

       
        $token = 'SXFn2GA8.HwmyddDZkgmSdODrmtkHu1TwqPpagnKZ5PPE';

        $bvnNumber = $request->input('bvn');

        $bvn = "22507170859"; 

        $data = [
            'token' => $token,
            'id' => $bvn,
            'isSubjectConsent' => true,
            'premiumBVN' => true, 
        ];

        try {
            $client = new Client();
            $response = $client->post($apiEndpoint, [
                'json' => $data,
            ]);

            $verificationResult = json_decode($response->getBody(), true);

            // Check if the verification was successful
            if (isset($verificationResult['status']) && $verificationResult['status'] === 'success') {
                $bvnData = $verificationResult['data'];
                // Process the BVN data as needed (e.g., display or store it)

                return response()->json($bvnData);
            } else {
                // Handle verification failure
                return response()->json(['error' => 'BVN verification failed'], 400);
            }
        } catch (Exception $e) {
            // Handle API request exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}