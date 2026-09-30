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
            session()->flash('error', __('Seul l\'acheteur peut ouvrir un litige sur cette commande.'));
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
            'title' => \App\Support\LocalizedMessage::store('events.dispute_opened_for_an_order'),
            'content' => \App\Support\LocalizedMessage::store('events.the_buyer_opened_a_dispute_for_reason', ['value1' => $this->order->product->title, 'value2' => $this->reason]),
        ]);

        $admins = User::where('is_admin', true)
            ->orWhereHas('roles', function ($q) {
                $q->where('name', 'admin');
            })->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => \App\Support\LocalizedMessage::store('events.new_dispute_awaiting_review'),
                'content' => \App\Support\LocalizedMessage::store('events.order_for_requires_your_arbitration', ['value1' => $this->order->id, 'value2' => $this->order->product->title]),
            ]);
        }

        $this->order->refresh();
        $this->reason = '';
        $this->isOpen = false;
        session()->flash('status', __('Litige ouvert avec succès. Les fonds sont sécurisés en ESCROW jusqu\'à arbitrage.'));
    }

    public function render()
    {
        return view('livewire.marketplace.dispute-button');
    }
}
