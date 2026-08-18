<?php

namespace App\Livewire\Marketplace;

use Livewire\Component;
use App\Models\Order;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class DisputeButton extends Component
{
    public Order $order;
    public $reason;
    public $isOpen = false;

    public function openDispute()
    {
        if ($this->order->buyer_id !== Auth::id()) {
            session()->flash('error', 'Seul l\'acheteur peut ouvrir un litige sur cette commande.');
            return;
        }
        
        $this->validate([
            'reason' => 'required|string|min:5|max:1000'
        ]);

        $this->order->update([
            'is_disputed' => true,
            'dispute_reason' => $this->reason,
            'dispute_status' => 'pending'
        ]);

        Notification::create([
            'user_id' => $this->order->product->vendor_id,
            'title' => 'Litige ouvert sur une commande ⚠️',
            'content' => "L'acheteur a ouvert un litige pour le produit '{$this->order->product->title}'. Motif : {$this->reason}",
        ]);

        $admins = User::where('is_admin', true)
            ->orWhereHas('roles', function ($q) {
                $q->where('name', 'admin');
            })->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Nouveau litige à traiter ⚖️',
                'content' => "La commande #{$this->order->id} pour '{$this->order->product->title}' nécessite votre arbitrage.",
            ]);
        }

        $this->order->refresh();
        $this->reason = '';
        $this->isOpen = false;
        session()->flash('status', 'Litige ouvert avec succès. Les fonds sont sécurisés en ESCROW jusqu\'à arbitrage.');
    }

    public function render()
    {
        return view('livewire.marketplace.dispute-button');
    }
}
