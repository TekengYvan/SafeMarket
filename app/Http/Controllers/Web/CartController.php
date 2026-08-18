<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Order;
use App\Models\Negotiation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Models\Transaction;

class CartController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $cartItems = $user->cartItems()->with('product.images')->get();
        
        // Optimisation N+1: Get all accepted negotiations for these products in one query
        $productIds = $cartItems->pluck('product_id');
        $negotiations = Negotiation::whereIn('product_id', $productIds)
            ->where('buyer_id', $user->id)
            ->where('status', 'accepted')
            ->get()
            ->keyBy('product_id');

        $total = 0;
        foreach($cartItems as $item) {
            $negotiation = $negotiations->get($item->product_id);
            $price = $negotiation ? $negotiation->proposed_price : $item->product->effective_price;
            $total += $price * $item->quantity;
        }

        return view('marketplace.cart', compact('cartItems', 'total', 'negotiations'));
    }

    public function store(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);
        
        $product = Product::findOrFail($request->product_id);
        
        if ($product->vendor_id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas acheter votre propre produit.');
        }

        // Check for accepted negotiation
        $negotiation = Negotiation::where('product_id', $product->id)
            ->where('buyer_id', auth()->id())
            ->where('status', 'accepted')
            ->first();

        $cartItem = auth()->user()->cartItems()->where('product_id', $product->id)->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            auth()->user()->cartItems()->create([
                'product_id' => $product->id, 
                'quantity' => 1
            ]);
        }

        $message = 'Produit ajouté au panier.';
        if ($negotiation) {
            $message .= ' Le prix négocié de ' . number_format($negotiation->proposed_price, 2) . ' FCFA sera appliqué.';
        }

        return redirect()->route('cart.index')->with('status', $message);
    }

    public function destroy(CartItem $cartItem)
    {
        if ($cartItem->user_id !== auth()->id()) {
            abort(403);
        }

        $cartItem->delete();
        return back()->with('status', 'Produit retiré du panier.');
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:30',
            'location' => 'required|string|max:1000',
            'payment_method' => 'nullable|string|in:wallet,campay',
        ]);

        $user = auth()->user();
        $cartItems = $user->cartItems()->with('product')->get();
        
        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Votre panier est vide.');
        }

        $productIds = $cartItems->pluck('product_id');
        $negotiations = Negotiation::whereIn('product_id', $productIds)
            ->where('buyer_id', $user->id)
            ->where('status', 'accepted')
            ->get()
            ->keyBy('product_id');

        $total = 0;
        foreach($cartItems as $item) {
            $negotiation = $negotiations->get($item->product_id);
            $price = $negotiation ? $negotiation->proposed_price : $item->product->effective_price;
            $total += $price * $item->quantity;
        }

        $paymentMethod = $request->input('payment_method', 'wallet');
        if ($paymentMethod === 'campay') {
            $campayService = app(\App\Services\CampayService::class);
            try {
                $tx = $campayService->deposit($user, (float)$total, $request->phone, 'momo');
                if ($tx->status === 'pending') {
                    return redirect()->route('cart.index')->with('status', "📱 Demande Mobile Money envoyée à votre numéro {$request->phone}. Veuillez valider avec votre code secret pour finaliser la commande.");
                }
                $user->refresh();
            } catch (\Exception $e) {
                return back()->with('error', 'Échec du paiement Mobile Money via Campay : ' . $e->getMessage());
            }
        }

        if ($user->balance < $total) {
            return back()->with('error', 'Solde insuffisant dans votre Wallet. Veuillez recharger votre solde ou utiliser le paiement Mobile Money direct.');
        }

        DB::transaction(function () use ($user, $cartItems, $negotiations, $total, $request) {
            // Deduct from wallet
            $user->decrement('balance', $total);

            // Record escrow hold transaction
            Transaction::create([
                'user_id' => $user->id,
                'type' => 'escrow_hold',
                'amount' => $total,
                'payment_method' => 'wallet',
                'reference' => 'ESC-' . strtoupper(Str::random(10)),
                'status' => 'successful',
                'description' => "Séquestre ESCROW pour " . $cartItems->count() . " article(s) commandé(s)",
            ]);

            foreach ($cartItems as $item) {
                $negotiation = $negotiations->get($item->product_id);
                $finalPrice = $negotiation ? $negotiation->proposed_price : $item->product->effective_price;

                // Create Order (ESCROW)
                $order = Order::create([
                    'buyer_id' => $user->id,
                    'product_id' => $item->product_id,
                    'amount' => $finalPrice * $item->quantity,
                    'phone' => $request->phone,
                    'location' => $request->location,
                    'status' => 'funds_held',
                    'release_code' => strtoupper(Str::random(8)),
                ]);

                // Notify Vendor
                \App\Models\Notification::create([
                    'user_id' => $item->product->vendor_id,
                    'title' => 'Nouveau produit vendu ! 🛍️',
                    'content' => "Votre produit '{$item->product->title}' a été acheté par {$user->name}. Téléphone: {$request->phone}, Lieu: {$request->location}. Veuillez préparer l'expédition.",
                ]);

                // Mark product as sold
                $item->product->update(['status' => 'sold']);
            }

            // Clear cart
            $user->cartItems()->delete();
        });

        return redirect()->route('orders.index')->with('status', 'Commande passée avec succès ! Vos fonds sont en ESCROW.');
    }
}
