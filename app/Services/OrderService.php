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
                'title' => \App\Support\LocalizedMessage::store('events.order_shipped'),
                'content' => \App\Support\LocalizedMessage::store('events.the_seller_has_shipped_your_order_for_tracking', ['value1' => $order->product->title, 'value2' => $trackingNumber]),
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
                'title' => \App\Support\LocalizedMessage::store('events.order_delivered'),
                'content' => \App\Support\LocalizedMessage::store('events.the_seller_has_marked_your_order_for_as', ['value1' => $order->product->title]),
            ]);
        }

        return $updated;
    }

    public function completeOrder(Order $order, string $releaseCode): bool
    {
        if (trim(strtoupper($order->release_code)) !== trim(strtoupper($releaseCode))) {
            throw new \Exception(__('Code de libération invalide.'));
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
                    'description' => \App\Support\LocalizedMessage::store('events.platform_commission_on_order', ['value1' => $order->id]),
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
                    'description' => \App\Support\LocalizedMessage::store('events.sale_payment_for_order', ['value1' => $order->id, 'value2' => $order->product->title]),
                ]);
            }

            // Send notification to vendor
            Notification::create([
                'user_id' => $order->product->vendor_id,
                'title' => \App\Support\LocalizedMessage::store('events.funds_released'),
                'content' => \App\Support\LocalizedMessage::store('events.the_buyer_has_confirmed_receipt_of_fcfa_has', ['value1' => $order->product->title, 'value2' => number_format($vendorAmount, 2)]),
            ]);

            return true;
        });
    }
}
