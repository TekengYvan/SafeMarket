<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KYCController extends Controller
{
    public function submit(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string|unique:users,phone_number,' . $request->user()->id,
            'id_card_photo' => 'required|image|max:2048',
        ]);

        $user = $request->user();

        if ($request->hasFile('id_card_photo')) {
            // Delete old photo if exists
            if ($user->id_card_photo) {
                Storage::disk('public')->delete($user->id_card_photo);
            }

            $path = $request->file('id_card_photo')->store('kyc_documents', 'public');
            $user->id_card_photo = $path;
        }

        $user->phone_number = $request->phone_number;
        $user->kyc_status = 'pending';
        $user->save();

        return response()->json([
            'message' => 'KYC documents submitted successfully. Status is now pending.',
            'user' => $user
        ]);
    }
}
