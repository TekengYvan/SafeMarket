<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'vendor', 'media'])
            ->where('status', 'available')
            ->where('is_in_stock', true);

        if ($request->filled('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category)
                  ->orWhereHas('parent', function($pq) use ($request) {
                      $pq->where('slug', $request->category);
                  });
            });
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $products = $query->latest()->paginate(12);
        $categories = Category::whereNull('parent_id')->with('children')->get();

        return view('marketplace.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        $product->load(['category', 'vendor', 'media', 'reviews.buyer']);

        $completedOrder = auth()->check()
            ? \App\Models\Order::where('product_id', $product->id)
                ->where('buyer_id', auth()->id())
                ->where('status', 'completed')
                ->whereDoesntHave('review')
                ->latest()
                ->first()
            : null;

        return view('marketplace.show', compact('product', 'completedOrder'));
    }

    public function spotlight()
    {
        $product = Product::first();
        if ($product) {
            return redirect()->route('products.show', $product);
        }
        return redirect()->route('home');
    }
}
