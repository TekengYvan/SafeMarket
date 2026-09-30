<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;

use App\Models\Transaction;
use Illuminate\Support\Str;

class DisputeManager extends Component
{
    public function resolve($orderId, $decision)
    {
        $order = Order::findOrFail($orderId);
        
        DB::transaction(function () use ($order, $decision) {
            if ($decision === 'buyer') {
                // Refund buyer
                $order->buyer->increment('balance', $order->amount);
                $order->update(['dispute_status' => 'resolved_to_buyer', 'status' => 'cancelled']);

                Transaction::create([
                    'user_id' => $order->buyer_id,
                    'type' => 'dispute_refund',
                    'amount' => $order->amount,
                    'payment_method' => 'wallet',
                    'reference' => 'REF-' . strtoupper(Str::random(10)),
                    'status' => 'successful',
                    'description' => \App\Support\LocalizedMessage::store('events.refund_following_a_dispute_for_order', ['value1' => $order->id]),
                ]);

                Notification::create([
                    'user_id' => $order->buyer_id,
                    'title' => \App\Support\LocalizedMessage::store('events.dispute_resolved_in_your_favour'),
                    'content' => \App\Support\LocalizedMessage::store('events.the_administrator_approved_your_dispute_for_fcfa_has', ['value1' => $order->product->title, 'value2' => number_format($order->amount, 2)]),
                ]);
            } else {
                // Release to vendor (minus 5% tax)
                $totalAmount = $order->amount;
                $taxAmount = $totalAmount * 0.05;
                $vendorAmount = $totalAmount - $taxAmount;

                $admin = User::role('admin')->first() ?? User::where('is_admin', true)->first();
                if ($admin) {
                    $admin->increment('balance', $taxAmount);
                    Transaction::create([
                        'user_id' => $admin->id,
                        'type' => 'escrow_release',
                        'amount' => $taxAmount,
                        'payment_method' => 'wallet',
                        'reference' => 'TAX-' . strtoupper(Str::random(10)),
                        'status' => 'successful',
                        'description' => \App\Support\LocalizedMessage::store('events.arbitration_commission_on_order', ['value1' => $order->id]),
                    ]);
                }
                
                $vendor = $order->product->vendor;
                if ($vendor) {
                    $vendor->increment('balance', $vendorAmount);
                    Transaction::create([
                        'user_id' => $vendor->id,
                        'type' => 'escrow_release',
                        'amount' => $vendorAmount,
                        'payment_method' => 'wallet',
                        'reference' => 'REL-' . strtoupper(Str::random(10)),
                        'status' => 'successful',
                        'description' => \App\Support\LocalizedMessage::store('events.sale_payment_after_arbitration_for_order', ['value1' => $order->id]),
                    ]);
                }

                $order->update(['dispute_status' => 'resolved_to_vendor', 'status' => 'completed']);

                Notification::create([
                    'user_id' => $order->product->vendor_id,
                    'title' => \App\Support\LocalizedMessage::store('events.dispute_decided_in_your_favour'),
                    'content' => \App\Support\LocalizedMessage::store('events.the_administrator_decided_the_dispute_for_in_your', ['value1' => $order->product->title, 'value2' => number_format($vendorAmount, 2)]),
                ]);
            }
        });

        session()->flash('status', __('Litige résolu avec succès.'));
    }

    public function render()
    {
        $disputes = Order::with(['buyer', 'product.vendor'])
            ->where('is_disputed', true)
            ->where('dispute_status', 'pending')
            ->latest()
            ->get();

        return view('livewire.admin.dispute-manager', compact('disputes'));
    }
}
