<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\AdminDashboardController;
use App\Http\Controllers\Web\Admin\UserController as AdminUserController;
use App\Http\Controllers\Web\MarketplaceController;
use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\NegotiationController;
use App\Http\Controllers\Web\Vendor\ProductController as VendorProductController;
use App\Http\Controllers\Web\Vendor\NegotiationController as VendorNegotiationController;
use App\Http\Controllers\Web\LocaleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $products = App\Models\Product::with('category')->take(4)->get();
    return view('welcome', compact('products'));
})->name('landing');

Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('home');
Route::get('/lang/{locale}', [LocaleController::class, 'change'])->name('lang.switch');
Route::get('/products/{product}', [MarketplaceController::class, 'show'])->name('products.show');
Route::get('/product-spotlight', [MarketplaceController::class, 'spotlight'])->name('products.spotlight');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }
        if ($user->hasRole('vendor')) {
            return redirect()->route('vendor.dashboard');
        }
        return view('dashboard');
    })->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cart & Checkout
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/ship', [OrderController::class, 'ship'])->name('orders.ship');
    Route::post('/orders/{order}/deliver', [OrderController::class, 'deliver'])->name('orders.deliver');
    Route::post('/orders/{order}/complete', [OrderController::class, 'complete'])->name('orders.complete');

    // Negotiations
    Route::get('/negotiations', [NegotiationController::class, 'index'])->name('negotiations.index');
    Route::get('/negotiations/{negotiation}', [NegotiationController::class, 'show'])->name('negotiations.show');
    Route::post('/negotiations', [NegotiationController::class, 'store'])->name('negotiations.store');
    Route::post('/negotiations/{negotiation}/message', [NegotiationController::class, 'sendMessage'])->name('negotiations.message');
    Route::patch('/negotiations/{negotiation}', [NegotiationController::class, 'update'])->name('negotiations.update');

// Vendor Shop
    Route::prefix('vendor')->name('vendor.')->group(function () {
        Route::get('/dashboard', function() {
            return view('vendor.dashboard');
        })->name('dashboard');
        Route::resource('products', VendorProductController::class);
        Route::get('/negotiations', [VendorNegotiationController::class, 'index'])->name('negotiations.index');
    });
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function() {
        return view('admin.dashboard');
    })->name('dashboard');
    Route::get('/kyc', [AdminDashboardController::class, 'kycIndex'])->name('kyc.index');
    Route::post('/kyc/{user}/verify', [AdminDashboardController::class, 'verifyKYC'])->name('kyc.verify');
    Route::get('/reports', [AdminDashboardController::class, 'reportsIndex'])->name('reports.index');
    
    // User Management
    Route::resource('users', AdminUserController::class);
});

// Public Tab Pages
Route::get('/on-sale', function () {
    $products = App\Models\Product::with('category')->where('condition', 'new')->take(3)->get();
    return view('pages.on-sale', compact('products'));
})->name('pages.on-sale');

Route::get('/blog', function () {
    return view('pages.blog');
})->name('pages.blog');

Route::get('/about', function () {
    return view('pages.about');
})->name('pages.about');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('pages.contact');

require __DIR__.'/auth.php';
