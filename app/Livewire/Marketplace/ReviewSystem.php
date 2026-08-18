<?php

namespace App\Livewire\Marketplace;

use Livewire\Component;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewSystem extends Component
{
    public Order $order;
    public $productRating = 5;
    public $vendorRating = 5;
    public $comment;
    public $hasReviewed = false;

    public function mount()
    {
        $this->hasReviewed = Review::where('order_id', $this->order->id)->exists();
    }

    public function submitReview()
    {
        if ($this->order->buyer_id !== Auth::id() || $this->order->status !== 'completed') {
            abort(403);
        }

        $this->validate([
            'productRating' => 'required|integer|min:1|max:5',
            'vendorRating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $review = Review::create([
            'order_id' => $this->order->id,
            'buyer_id' => Auth::id(),
            'product_id' => $this->order->product_id,
            'vendor_id' => $this->order->product->vendor_id,
            'product_rating' => $this->productRating,
            'vendor_rating' => $this->vendorRating,
            'comment' => $this->comment,
        ]);

        // Update Trust Score
        $scoreChange = match((int)$this->vendorRating) {
            5 => 2,
            4 => 1,
            3 => 0,
            2 => -2,
            1 => -5,
            default => 0,
        };

        $vendor = $this->order->product->vendor;
        $vendor->trust_score = max(0, min(100, $vendor->trust_score + $scoreChange));
        $vendor->save();

        $this->hasReviewed = true;
        session()->flash('status', 'Merci pour votre avis !');
    }

    public function render()
    {
        return view('livewire.marketplace.review-system');
    }
}
