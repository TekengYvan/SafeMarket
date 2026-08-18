<?php

namespace App\Livewire\Vendor;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class ProductManager extends Component
{
    use WithFileUploads;

    public $products;
    public $categories;
    public $isEditing = false;
    public $editingId;
    public $showFormModal = false;
    
    // Form fields
    public $title, $description, $price, $discount_price, $is_on_sale = false, $category_id, $condition = 'new', $location, $is_in_stock = true, $has_invoice = false;
    public $images = [];
    public $invoiceFile;

    public function mount()
    {
        $this->categories = Category::whereNull('parent_id')->with('children')->get();
        $this->loadProducts();
    }

    public function loadProducts()
    {
        $this->products = Auth::user()->products()->with('category', 'media')->latest()->get();
    }

    public function resetForm()
    {
        $this->reset(['title', 'description', 'price', 'discount_price', 'is_on_sale', 'category_id', 'condition', 'location', 'is_in_stock', 'has_invoice', 'images', 'invoiceFile', 'isEditing', 'editingId', 'showFormModal']);
        $this->condition = 'new';
        $this->is_in_stock = true;
        $this->is_on_sale = false;
        $this->has_invoice = false;
        $this->resetValidation();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        if ($this->categories->isNotEmpty()) {
            $firstCat = $this->categories->first();
            $this->category_id = $firstCat->children->isNotEmpty() ? $firstCat->children->first()->id : $firstCat->id;
        }
        $this->showFormModal = true;
    }

    public function save()
    {
        if ($this->isEditing) {
            $this->update();
        } else {
            $this->store();
        }
    }

    public function store()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'is_on_sale' => 'boolean',
            'category_id' => 'required|exists:categories,id',
            'condition' => 'required|in:new,like_new,good,fair',
            'location' => 'required|string',
            'images.*' => 'image|max:2048',
            'invoiceFile' => 'nullable|file|mimes:pdf,jpg,png|max:4096',
        ]);

        $product = Auth::user()->products()->create([
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'discount_price' => $this->is_on_sale ? ($this->discount_price ?: null) : null,
            'is_on_sale' => (bool) $this->is_on_sale,
            'category_id' => $this->category_id,
            'condition' => $this->condition,
            'location' => $this->location,
            'is_in_stock' => (bool) $this->is_in_stock,
            'has_invoice' => (bool) $this->has_invoice,
            'status' => 'available',
        ]);

        if (!empty($this->images)) {
            foreach ($this->images as $image) {
                if (is_object($image) && method_exists($image, 'getRealPath')) {
                    try {
                        $product->addMedia($image->getRealPath())->toMediaCollection('products');
                    } catch (\Throwable $e) {
                        \Illuminate\Support\Facades\Log::warning('Media upload error: ' . $e->getMessage());
                    }
                }
            }
        }

        if ($this->has_invoice && $this->invoiceFile && is_object($this->invoiceFile) && method_exists($this->invoiceFile, 'getRealPath')) {
            try {
                $product->addMedia($this->invoiceFile->getRealPath())->toMediaCollection('invoices');
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Invoice upload error: ' . $e->getMessage());
            }
        }

        $this->resetForm();
        $this->loadProducts();
        session()->flash('status', 'Produit ajouté avec succès.');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        if ($product->vendor_id !== Auth::id()) {
            abort(403);
        }

        $this->editingId = $id;
        $this->title = $product->title;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->discount_price = $product->discount_price;
        $this->is_on_sale = (bool) $product->is_on_sale;
        $this->category_id = $product->category_id;
        $this->condition = $product->condition;
        $this->location = $product->location;
        $this->is_in_stock = (bool) $product->is_in_stock;
        $this->has_invoice = (bool) $product->has_invoice;
        $this->images = [];
        $this->invoiceFile = null;
        $this->isEditing = true;
        $this->showFormModal = true;
    }

    public function update()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'is_on_sale' => 'boolean',
            'category_id' => 'required|exists:categories,id',
            'condition' => 'required|in:new,like_new,good,fair',
            'location' => 'required|string',
            'images.*' => 'nullable|image|max:2048',
            'invoiceFile' => 'nullable|file|mimes:pdf,jpg,png|max:4096',
        ]);

        $product = Product::findOrFail($this->editingId);
        if ($product->vendor_id !== Auth::id()) {
            abort(403);
        }

        $product->update([
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'discount_price' => $this->is_on_sale ? ($this->discount_price ?: null) : null,
            'is_on_sale' => (bool) $this->is_on_sale,
            'category_id' => $this->category_id,
            'condition' => $this->condition,
            'location' => $this->location,
            'is_in_stock' => (bool) $this->is_in_stock,
            'has_invoice' => (bool) $this->has_invoice,
        ]);

        if (!empty($this->images)) {
            foreach ($this->images as $image) {
                if (is_object($image) && method_exists($image, 'getRealPath')) {
                    try {
                        $product->addMedia($image->getRealPath())->toMediaCollection('products');
                    } catch (\Throwable $e) {
                        \Illuminate\Support\Facades\Log::warning('Media upload update error: ' . $e->getMessage());
                    }
                }
            }
        }

        if ($this->has_invoice && $this->invoiceFile && is_object($this->invoiceFile) && method_exists($this->invoiceFile, 'getRealPath')) {
            try {
                $product->clearMediaCollection('invoices');
                $product->addMedia($this->invoiceFile->getRealPath())->toMediaCollection('invoices');
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Invoice update error: ' . $e->getMessage());
            }
        }

        $this->resetForm();
        $this->loadProducts();
        session()->flash('status', 'Produit mis à jour avec succès.');
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);
        if ($product->vendor_id !== Auth::id()) {
            abort(403);
        }
        $product->delete();
        $this->loadProducts();
        session()->flash('status', 'Produit supprimé avec succès.');
    }

    public function render()
    {
        return view('livewire.vendor.product-manager');
    }
}
