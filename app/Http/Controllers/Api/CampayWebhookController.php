<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CampayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CampayWebhookController extends Controller
{
    public function handle(Request $request, CampayService $campayService)
    {
        Log::info('Campay Webhook hit: ', $request->all());

        try {
            $processed = $campayService->handleWebhook($request->all());

            return response()->json([
                'status' => 'success',
                'processed' => $processed,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Campay Webhook Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
