<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'reported_id' => 'required|exists:users,id',
            'product_id' => 'nullable|exists:products,id',
            'reason' => 'required|string|max:500',
        ]);

        if ($request->reported_id === $request->user()->id) {
            return response()->json(['message' => 'You cannot report yourself'], 400);
        }

        $report = Report::create([
            'reporter_id' => $request->user()->id,
            'reported_id' => $request->reported_id,
            'product_id' => $request->product_id,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Report submitted successfully. Our team will review it.',
            'report' => $report
        ], 201);
    }

    public function index(Request $request)
    {
        if (!$request->user()->is_admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $reports = Report::with(['reporter', 'reported', 'product'])->latest()->get();

        return response()->json($reports);
    }
}
