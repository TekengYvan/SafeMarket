<?php

namespace App\Livewire\Vendor;

use Livewire\Component;
use App\Models\Order;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class OrderManager extends Component
{
    public $trackingNumber = [];

    public function shipOrder($orderId)
    {
        $this->validate([
            "trackingNumber.{$orderId}" => 'required|string|max:100'
        ], [
            "trackingNumber.{$orderId}.required" => 'Le numéro de suivi est requis.'
        ]);

        $order = Order::findOrFail($orderId);
        if ($order->product->vendor_id !== Auth::id()) {
            abort(403);
        }

        $track = $this->trackingNumber[$orderId];

        $order->update([
            'status' => 'shipped',
            'tracking_number' => $track
        ]);

        // Send Notification to Buyer Client
        Notification::create([
            'user_id' => $order->buyer_id,
            'title' => \App\Support\LocalizedMessage::store('events.order_shipped_2'),
            'content' => \App\Support\LocalizedMessage::store('events.the_seller_has_shipped_your_order_for_tracking_2', ['value1' => $order->product->title, 'value2' => $track]),
        ]);

        session()->flash("status-{$orderId}", __('Commande marquée comme expédiée.'));
    }

    public function deliverOrder($orderId)
    {
        $order = Order::findOrFail($orderId);
        if ($order->product->vendor_id !== Auth::id()) {
            abort(403);
        }

        $order->update([
            'status' => 'delivered'
        ]);

        Notification::create([
            'user_id' => $order->buyer_id,
            'title' => \App\Support\LocalizedMessage::store('events.order_delivered_2'),
            'content' => \App\Support\LocalizedMessage::store('events.the_seller_has_marked_your_order_for_as_2', ['value1' => $order->product->title]),
        ]);

        session()->flash("status-{$orderId}", __('Commande marquée comme livrée.'));
    }

    public function render()
    {
        $orders = Order::with(['product', 'buyer'])
            ->whereHas('product', function ($query) {
                $query->where('vendor_id', Auth::id());
            })
            ->latest()
            ->get();

        return view('livewire.vendor.order-manager', [
            'orders' => $orders
        ]);
    }
}
