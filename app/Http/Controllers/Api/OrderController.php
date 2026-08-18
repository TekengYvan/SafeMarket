<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }
    public function index(Request $request)
    {
        $user = $request->user();
        $orders = Order::with(['product', 'buyer'])
            ->where('buyer_id', $user->id)
            ->orWhereHas('product', function($q) use ($user) {
                $q->where('vendor_id', $user->id);
            })
            ->latest()
            ->get();

        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->status !== 'available') {
            return response()->json(['message' => 'Product is not available'], 400);
        }

        if ($product->vendor_id === $request->user()->id) {
            return response()->json(['message' => 'You cannot buy your own product'], 400);
        }

        $order = Order::create([
            'buyer_id' => $request->user()->id,
            'product_id' => $product->id,
            'amount' => $product->price,
            'status' => 'awaiting_payment',
            'release_code' => strtoupper(Str::random(8)),
        ]);

        $product->update(['status' => 'sold']);

        return response()->json($order, 201);
    }

    public function pay(Request $request, Order $order)
    {
        if ($order->buyer_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($order->status !== 'awaiting_payment') {
            return response()->json(['message' => 'Order is not in awaiting payment status'], 400);
        }

        // Simulate payment success
        $order->update(['status' => 'funds_held']);

        return response()->json(['message' => 'Payment successful, funds are now held in ESCROW', 'order' => $order]);
    }

    public function ship(Request $request, Order $order)
    {
        if ($order->product->vendor_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($order->status !== 'funds_held') {
            return response()->json(['message' => 'Order must be paid before shipping'], 400);
        }

        $request->validate([
            'tracking_number' => 'required|string',
        ]);

        $this->orderService->shipOrder($order, $request->tracking_number);

        return response()->json(['message' => 'Order marked as shipped', 'order' => $order]);
    }

    public function complete(Request $request, Order $order)
    {
        if ($order->buyer_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($order->status !== 'shipped') {
            return response()->json(['message' => 'Order must be shipped before completion'], 400);
        }

        $request->validate([
            'release_code' => 'required|string',
        ]);

        try {
            $this->orderService->completeOrder($order, $request->release_code);
            return response()->json(['message' => 'Order completed, funds released to vendor', 'order' => $order->fresh()]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
