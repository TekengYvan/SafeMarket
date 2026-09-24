<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;

use App\Models\Transaction;
use Illuminate\Support\Str;

class OrderService
{
    public function shipOrder(Order $order, string $trackingNumber): bool
    {
        $updated = $order->update([
            'status' => 'shipped',
            'tracking_number' => $trackingNumber
        ]);

        if ($updated) {
            Notification::create([
                'user_id' => $order->buyer_id,
                'title' => 'Order Shipped 📦',
                'content' => "The Vendor has shipped the Product '{$order->product->title}'. Tracking Number: {$trackingNumber}.",
            ]);
        }

        return $updated;
    }

    public function deliverOrder(Order $order): bool
    {
        $updated = $order->update([
            'status' => 'delivered'
        ]);

        if ($updated) {
            Notification::create([
                'user_id' => $order->buyer_id,
                'title' => 'Order delivered 📦',
                'content' => "Then merchant has marked your order for the product '{$order->product->title}' as delivered. please confirm receipt to release the fund.",
            ]);
        }

        return $updated;
    }

    public function completeOrder(Order $order, string $releaseCode): bool
    {
        if (trim(strtoupper($order->release_code)) !== trim(strtoupper($releaseCode))) {
            throw new \Exception('Code de libération invalide.');
        }

        return DB::transaction(function () use ($order) {
            $order->update(['status' => 'completed']);
            
            $totalAmount = $order->amount;
            $taxAmount = $totalAmount * 0.05; // 5% Platform Tax
            $vendorAmount = $totalAmount - $taxAmount;

            // Find admin user to receive platform tax (via role or is_admin flag)
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
                    'description' => "Commission plateforme 5% sur la commande #{$order->id}",
                ]);
            }

            // Release funds to vendor
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
                    'description' => "Paiement vente commande #{$order->id} ('{$order->product->title}')",
                ]);
            }

            // Send notification to vendor
            Notification::create([
                'user_id' => $order->product->vendor_id,
                'title' => 'Fund release 💰',
                'content' => "The buyer has valid the receipt of the product '{$order->product->title}'. The amount " . number_format($vendorAmount, 2) . " FCFA was credited to your account.",
            ]);

            return true;
        });
    }
}
