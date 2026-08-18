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
            'title' => 'Commande expédiée 📦',
            'content' => "Le commerçant a expédié votre commande pour le produit '{$order->product->title}'. Numéro de suivi : {$track}.",
        ]);

        session()->flash("status-{$orderId}", "Commande marquée comme expédiée.");
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
            'title' => 'Commande livrée 📦',
            'content' => "Le commerçant a marqué votre commande pour le produit '{$order->product->title}' comme livrée. Veuillez confirmer la réception pour libérer les fonds.",
        ]);

        session()->flash("status-{$orderId}", "Commande marquée comme livrée.");
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
