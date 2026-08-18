<?php

namespace App\Http\Controllers\Web\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = auth()->user()->products()->with('category')->latest()->paginate(10);
        return view('vendor.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::whereNull('parent_id')->with('children')->get();
        return view('vendor.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'condition' => 'required|in:new,like_new,good,fair',
            'location' => 'required|string',
            'images.*' => 'image|max:2048',
            'is_in_stock' => 'boolean',
        ]);

        $product = auth()->user()->products()->create(array_merge(
            $request->except(['is_in_stock', 'images']),
            ['is_in_stock' => $request->boolean('is_in_stock', true)]
        ));

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $product->addMedia($image)->toMediaCollection('products');
            }
        }

        return redirect()->route('vendor.products.index')->with('status', 'Produit ajouté avec succès.');
    }

    public function edit(Product $product)
    {
        if ($product->vendor_id !== auth()->id()) abort(403);
        $categories = Category::whereNull('parent_id')->with('children')->get();
        return view('vendor.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        if ($product->vendor_id !== auth()->id()) abort(403);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'condition' => 'required|in:new,like_new,good,fair',
            'location' => 'required|string|max:255',
            'is_in_stock' => 'boolean',
            'images.*' => 'image|max:2048',
        ]);

        $product->update(array_merge(
            collect($validated)->except(['is_in_stock', 'images'])->all(),
            ['is_in_stock' => $request->boolean('is_in_stock')]
        ));

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $product->addMedia($image)->toMediaCollection('products');
            }
        }

        return redirect()->route('vendor.products.index')->with('status', 'Produit mis à jour.');
    }

    public function destroy(Product $product)
    {
        if ($product->vendor_id !== auth()->id()) abort(403);
        $product->delete();
        return back()->with('status', 'Produit supprimé.');
    }
}
