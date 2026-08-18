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
                'title' => 'Commande expédiée 📦',
                'content' => "Le commerçant a expédié votre commande pour le produit '{$order->product->title}'. Numéro de suivi : {$trackingNumber}.",
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
                'title' => 'Commande livrée 📦',
                'content' => "Le commerçant a marqué votre commande pour le produit '{$order->product->title}' comme livrée. Veuillez confirmer la réception pour libérer les fonds.",
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
                'title' => 'Fonds libérés 💰',
                'content' => "L'acheteur a validé la réception du produit '{$order->product->title}'. Le montant de " . number_format($vendorAmount, 2) . " FCFA a été crédité sur votre solde.",
            ]);

            return true;
        });
    }
}
