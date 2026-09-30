<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CartItem;
use App\Models\Negotiation;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Notification;
use App\Services\CampayService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartComponent extends Component
{
    public $cartItems;
    public $total = 0;
    public $negotiations;

    // Checkout form inputs
    public $phone = '';
    public $location = '';
    public $paymentMethod = 'wallet';
    public $campayMethod = 'momo'; // 'momo' or 'om'

    // Pending payment state
    public $pendingTxId = null;
    public $pendingTxReference = null;
    public $isWaitingPayment = false;
    public $paymentStatusMessage = '';
    public $paymentErrorMessage = '';

    public function mount()
    {
        $this->phone = Auth::user()->phone_number ?? '';
        $this->loadCart();
        $this->paymentMethod = Auth::user()->balance >= $this->total ? 'wallet' : 'campay';
    }

    public function loadCart()
    {
        $user = Auth::user();
        $this->cartItems = $user->cartItems()->with(['product.images', 'product.vendor'])->get();
        
        $productIds = $this->cartItems->pluck('product_id');
        $this->negotiations = Negotiation::whereIn('product_id', $productIds)
            ->where('buyer_id', $user->id)
            ->where('status', 'accepted')
            ->get()
            ->keyBy('product_id');

        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total = 0;
        foreach($this->cartItems as $item) {
            $negotiation = $this->negotiations->get($item->product_id);
            $price = $negotiation ? $negotiation->proposed_price : $item->product->effective_price;
            $this->total += $price * $item->quantity;
        }
    }

    public function incrementQuantity($itemId)
    {
        $cartItem = CartItem::find($itemId);
        if ($cartItem && $cartItem->user_id === Auth::id()) {
            $cartItem->increment('quantity');
            $this->loadCart();
            $this->dispatch('cartUpdated');
        }
    }

    public function decrementQuantity($itemId)
    {
        $cartItem = CartItem::find($itemId);
        if ($cartItem && $cartItem->user_id === Auth::id()) {
            if ($cartItem->quantity > 1) {
                $cartItem->decrement('quantity');
            } else {
                $cartItem->delete();
            }
            $this->loadCart();
            $this->dispatch('cartUpdated');
        }
    }

    public function removeItem($itemId)
    {
        $cartItem = CartItem::find($itemId);
        if ($cartItem && $cartItem->user_id === Auth::id()) {
            $cartItem->delete();
            $this->loadCart();
            $this->dispatch('cartUpdated');
        }
    }

    public function clearCart()
    {
        Auth::user()->cartItems()->delete();
        $this->loadCart();
        $this->dispatch('cartUpdated');
    }

    public function submitCheckout(CampayService $campayService)
    {
        $this->validate([
            'phone' => 'required|string|min:9|max:30',
            'location' => 'required|string|min:3|max:1000',
            'paymentMethod' => 'required|in:wallet,campay',
        ]);

        $this->loadCart();

        if ($this->cartItems->isEmpty()) {
            session()->flash('error', __('Votre panier est vide.'));
            return;
        }

        $user = Auth::user();

        if ($this->paymentMethod === 'wallet') {
            if ($user->balance < $this->total) {
                $this->paymentErrorMessage = __('Solde insuffisant dans votre Wallet. Veuillez recharger votre compte ou choisir le paiement par CamPay (MoMo/OM).');
                return;
            }

            $this->finalizeOrder();
            return;
        }

        // CamPay Mobile Money Checkout
        try {
            $this->paymentErrorMessage = '';
            $tx = $campayService->deposit($user, (float) $this->total, $this->phone, $this->campayMethod);

            if ($tx->status === 'successful') {
                // If instant/synchronous
                $this->finalizeOrder();
            } elseif ($tx->status === 'pending') {
                // Asynchronous: User must validate USSD prompt on their mobile phone
                $this->pendingTxId = $tx->id;
                $this->pendingTxReference = $tx->reference;
                $this->isWaitingPayment = true;
                $this->paymentStatusMessage = $tx->description ?? __('📱 Demande envoyée à votre téléphone (:value1) ! Veuillez composer votre code secret Mobile Money pour valider le paiement.', ['value1' => $this->phone]);
            } else {
                $this->paymentErrorMessage = $tx->description ?? __('Échec de l\'envoi de la demande Mobile Money.');
            }
        } catch (\Exception $e) {
            $this->paymentErrorMessage = __('Erreur: :value1', ['value1' => $e->getMessage()]);
        }
    }

    /**
     * Poll the status of the pending Campay transaction while waiting for mobile validation.
     */
    public function checkPaymentStatus(CampayService $campayService)
    {
        if (! $this->pendingTxId || ! $this->isWaitingPayment) {
            return;
        }

        $tx = Transaction::where('id', $this->pendingTxId)->where('user_id', Auth::id())->first();
        if (! $tx) {
            $this->isWaitingPayment = false;
            return;
        }

        $updatedTx = $campayService->checkTransactionStatus($tx);

        if ($updatedTx->status === 'successful') {
            $this->isWaitingPayment = false;
            $this->pendingTxId = null;
            $this->finalizeOrder();
        } elseif ($updatedTx->status === 'failed') {
            $this->isWaitingPayment = false;
            $this->pendingTxId = null;
            $this->paymentErrorMessage = __('❌ Le paiement Mobile Money a été refusé, annulé ou a expiré sur votre téléphone.');
        }
    }

    public function cancelWaitingPayment()
    {
        $this->isWaitingPayment = false;
        $this->pendingTxId = null;
    }

    /**
     * Finalize the order creation, hold funds in ESCROW, notify vendors, clear cart.
     */
    protected function finalizeOrder()
    {
        $user = Auth::user()->fresh();
        $this->loadCart();

        if ($user->balance < $this->total) {
            $this->paymentErrorMessage = __('Solde insuffisant pour finaliser l\'ESCROW.');
            return;
        }

        DB::transaction(function () use ($user) {
            // Deduct from wallet
            $user->decrement('balance', $this->total);

            // Record escrow hold transaction
            Transaction::create([
                'user_id' => $user->id,
                'type' => 'escrow_hold',
                'amount' => $this->total,
                'payment_method' => $this->paymentMethod === 'campay' ? $this->campayMethod : 'wallet',
                'reference' => 'ESC-' . strtoupper(Str::random(10)),
                'status' => 'successful',
                'description' => \App\Support\LocalizedMessage::store('events.escrow_hold_for_ordered_item_s', ['value1' => $this->cartItems->count()]),
            ]);

            foreach ($this->cartItems as $item) {
                $negotiation = $this->negotiations->get($item->product_id);
                $finalPrice = $negotiation ? $negotiation->proposed_price : $item->product->effective_price;

                // Create Order (ESCROW)
                Order::create([
                    'buyer_id' => $user->id,
                    'product_id' => $item->product_id,
                    'amount' => $finalPrice * $item->quantity,
                    'phone' => $this->phone,
                    'location' => $this->location,
                    'status' => 'funds_held',
                    'release_code' => strtoupper(Str::random(8)),
                ]);

                // Notify Vendor
                Notification::create([
                    'user_id' => $item->product->vendor_id,
                    'title' => \App\Support\LocalizedMessage::store('events.new_product_sold'),
                    'content' => \App\Support\LocalizedMessage::store('events.your_product_was_purchased_by_phone_location_please', ['value1' => $item->product->title, 'value2' => $user->name, 'value3' => $this->phone, 'value4' => $this->location]),
                ]);

                // Mark product as sold
                $item->product->update(['status' => 'sold']);
            }

            // Clear cart
            $user->cartItems()->delete();
        });

        session()->flash('status', __('🎉 Commande passée avec succès ! Vos fonds sont sécurisés en ESCROW.'));
        return redirect()->route('orders.index');
    }

    public function render()
    {
        return view('livewire.cart-component');
    }
}
