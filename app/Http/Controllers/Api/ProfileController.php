<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        
        // Basic trust score logic: 
        // Start with base score, can be modified by KYC status
        $effectiveTrustScore = $user->trust_score;
        if ($user->kyc_status === 'verified') {
            $effectiveTrustScore += 20; // Bonus for verified KYC
        }

        return response()->json([
            'profile' => $user,
            'effective_trust_score' => min(100, $effectiveTrustScore),
            'kyc_status' => $user->kyc_status
        ]);
    }
}
