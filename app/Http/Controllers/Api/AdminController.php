<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function indexPendingKYC(Request $request)
    {
        if (!$request->user()->is_admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $pending = User::where('kyc_status', 'pending')
            ->whereNotNull('id_card_photo')
            ->get();

        return response()->json($pending);
    }

    public function verifyKYC(Request $request, User $user)
    {
        if (!$request->user()->is_admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|in:verified,rejected',
        ]);

        $user->kyc_status = $request->status;
        $user->save();

        return response()->json([
            'message' => "KYC status updated to {$request->status}",
            'user' => $user
        ]);
    }
}
