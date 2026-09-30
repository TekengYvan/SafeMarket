<?php

namespace App\Services;

use App\Models\Negotiation;
use App\Models\Product;
use App\Models\Message;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NegotiationService
{
    public function createNegotiation(array $data): Negotiation
    {
        $product = Product::findOrFail($data['product_id']);

        if ($product->vendor_id === Auth::id()) {
            throw new \Exception(__('Vous ne pouvez pas négocier votre propre produit.'));
        }

        if ($product->status !== 'available' || ! $product->is_in_stock) {
            throw new \Exception(__('Ce produit n’est plus disponible à la négociation.'));
        }

        return DB::transaction(function () use ($data, $product) {
            $negotiation = Negotiation::create([
                'product_id' => $product->id,
                'buyer_id' => Auth::id(),
                'seller_id' => $product->vendor_id,
                'original_price' => $product->effective_price,
                'proposed_price' => $data['proposed_price'],
                'message' => $data['message'] ?? null,
                'status' => 'pending',
            ]);

            if (! empty($data['message'])) {
                $negotiation->messages()->create([
                    'user_id' => Auth::id(),
                    'content' => trim($data['message']),
                ]);
            }

            // Notification pour le vendeur
            try {
                Notification::create([
                    'user_id' => $product->vendor_id,
                    'title' => \App\Support\LocalizedMessage::store('events.new_negotiation_offer'),
                    'content' => \App\Support\LocalizedMessage::store('events.offers_you_fcfa_for', ['value1' => (Auth::user()->name ?? __('Un client')), 'value2' => number_format($data['proposed_price'], 0, ',', ' '), 'value3' => $product->title]),
                ]);
            } catch (\Throwable $e) {
                // Ignore notification error to not break transaction
            }

            return $negotiation;
        });
    }

    public function sendMessage(Negotiation $negotiation, string $content): Message
    {
        $this->authorizeParticipant($negotiation);

        $message = $negotiation->messages()->create([
            'user_id' => Auth::id(),
            'content' => $content,
        ]);

        // Notification pour le destinataire
        $recipientId = Auth::id() === $negotiation->buyer_id ? $negotiation->seller_id : $negotiation->buyer_id;
        try {
            Notification::create([
                'user_id' => $recipientId,
                'title' => \App\Support\LocalizedMessage::store('events.new_negotiation_message'),
                'content' => \App\Support\LocalizedMessage::store('events.sent_you_a_message_about', ['value1' => (Auth::user()->name ?? __('Un utilisateur')), 'value2' => ($negotiation->product->title ?? __('un produit'))]),
            ]);
        } catch (\Throwable $e) {
            // Ignore
        }

        return $message;
    }

    public function updateStatus(Negotiation $negotiation, string $status): bool
    {
        if ($negotiation->status !== 'pending') {
            throw new \Exception(__('Cette négociation est déjà terminée.'));
        }
        if ($status === 'cancelled') {
            if ($negotiation->buyer_id !== Auth::id()) {
                throw new \Exception(__('Seul l\'acheteur peut annuler une négociation.'));
            }
        } else {
            if ($negotiation->seller_id !== Auth::id()) {
                throw new \Exception(__('Seul le vendeur peut accepter ou rejeter une proposition.'));
            }
        }

        $updated = $negotiation->update(['status' => $status]);

        if ($updated) {
            $statusText = match($status) {
                'accepted' => 'acceptée',
                'rejected' => 'refusée',
                'cancelled' => 'annulée',
                default => $status
            };

            $recipientId = Auth::id() === $negotiation->buyer_id ? $negotiation->seller_id : $negotiation->buyer_id;
            try {
                Notification::create([
                    'user_id' => $recipientId,
                    'title' => \App\Support\LocalizedMessage::store('events.negotiation', ['value1' => \App\Support\LocalizedMessage::label($statusText)]),
                    'content' => \App\Support\LocalizedMessage::store('events.the_offer_for_has_been_2', ['value1' => ($negotiation->product->title ?? __('the product')), 'value2' => \App\Support\LocalizedMessage::label($statusText)]),
                ]);
            } catch (\Throwable $e) {
                // Ignore
            }
        }

        return $updated;
    }

    private function authorizeParticipant(Negotiation $negotiation): void
    {
        if ($negotiation->buyer_id !== Auth::id() && $negotiation->seller_id !== Auth::id()) {
            abort(403);
        }
    }
}

