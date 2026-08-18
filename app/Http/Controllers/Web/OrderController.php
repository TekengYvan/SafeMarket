<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Http\Requests\ShipOrderRequest;
use App\Http\Requests\CompleteOrderRequest;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        $user = auth()->user();
        $purchases = Order::with('product.vendor')->where('buyer_id', $user->id)->latest()->get();
        $sales = Order::with('product')->whereHas('product', function($q) use ($user) {
            $q->where('vendor_id', $user->id);
        })->latest()->get();

        return view('orders.index', compact('purchases', 'sales'));
    }

    public function show(Order $order)
    {
        if ($order->buyer_id !== auth()->id() && $order->product->vendor_id !== auth()->id()) {
            abort(403);
        }

        return view('orders.show', compact('order'));
    }

    public function ship(ShipOrderRequest $request, Order $order)
    {
        $this->orderService->shipOrder($order, $request->validated()['tracking_number']);

        return back()->with('status', 'Commande marquée comme expédiée.');
    }

    public function deliver(Order $order)
    {
        if ($order->product->vendor_id !== auth()->id()) {
            abort(403);
        }

        $this->orderService->deliverOrder($order);

        return back()->with('status', 'Commande marquée comme livrée. En attente de confirmation de l\'acheteur.');
    }

    public function complete(CompleteOrderRequest $request, Order $order)
    {
        try {
            $this->orderService->completeOrder($order, $request->validated()['release_code']);
            return back()->with('status', 'Commande terminée. Les fonds (moins 5% de taxe plateforme) ont été libérés au vendeur.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
