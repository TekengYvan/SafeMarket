<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $order = Order::findOrFail($request->order_id);

        if ($order->status !== 'completed') {
            return response()->json(['message' => 'You can only review completed orders'], 400);
        }

        if ($order->buyer_id !== $request->user()->id) {
            return response()->json(['message' => 'Only the buyer can review the transaction'], 403);
        }

        // Check if review already exists
        if (Review::where('order_id', $order->id)->exists()) {
            return response()->json(['message' => 'You have already reviewed this transaction'], 400);
        }

        $reviewee = $order->product->vendor;

        $review = Review::create([
            'order_id' => $order->id,
            'buyer_id' => $request->user()->id,
            'product_id' => $order->product_id,
            'vendor_id' => $reviewee->id,
            'product_rating' => $request->rating,
            'vendor_rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Update Trust Score
        $scoreChange = match($request->rating) {
            5 => 2,
            4 => 1,
            3 => 0,
            2 => -2,
            1 => -5,
            default => 0,
        };

        $reviewee->trust_score = max(0, min(100, $reviewee->trust_score + $scoreChange));
        $reviewee->save();

        return response()->json([
            'message' => 'Review submitted successfully',
            'review' => $review,
            'new_trust_score' => $reviewee->trust_score
        ]);
    }
}
