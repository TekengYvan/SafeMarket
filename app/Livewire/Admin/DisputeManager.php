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
                    'description' => "Remboursement suite à litige pour commande #{$order->id}",
                ]);

                Notification::create([
                    'user_id' => $order->buyer_id,
                    'title' => 'Litige résolu en votre faveur ⚖️',
                    'content' => "L'administrateur a validé votre litige pour '{$order->product->title}'. Le montant de " . number_format($order->amount, 2) . " FCFA a été recrédité sur votre Wallet.",
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
                        'description' => "Commission arbitrage 5% sur commande #{$order->id}",
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
                        'description' => "Versement vente après arbitrage pour commande #{$order->id}",
                    ]);
                }

                $order->update(['dispute_status' => 'resolved_to_vendor', 'status' => 'completed']);

                Notification::create([
                    'user_id' => $order->product->vendor_id,
                    'title' => 'Litige tranché en votre faveur ⚖️',
                    'content' => "L'administrateur a tranché le litige pour '{$order->product->title}' en votre faveur. Le montant net de " . number_format($vendorAmount, 2) . " FCFA a été versé sur votre solde.",
                ]);
            }
        });

        session()->flash('status', 'Litige résolu avec succès.');
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
