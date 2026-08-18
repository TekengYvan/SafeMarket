<?php

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\CartItem;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Seed roles if not already present
    if (Role::count() === 0) {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'vendor']);
        Role::create(['name' => 'buyer']);
    }
});

it('requires phone and location to place an order', function () {
    $buyer = User::factory()->create(['balance' => 100000]);
    $buyer->assignRole('buyer');

    $vendor = User::factory()->create();
    $vendor->assignRole('vendor');

    $category = Category::create([
        'name' => 'Test Category',
        'slug' => 'test-category'
    ]);

    $product = Product::create([
        'vendor_id' => $vendor->id,
        'category_id' => $category->id,
        'title' => 'Test Product',
        'description' => 'Test Product Description',
        'price' => 5000,
        'condition' => 'new',
        'status' => 'available',
        'location' => 'Douala',
        'is_in_stock' => true,
    ]);

    CartItem::create([
        'user_id' => $buyer->id,
        'product_id' => $product->id,
        'quantity' => 1,
    ]);

    // Request checkout without details -> should fail validation
    $response = $this->actingAs($buyer)->post(route('checkout'), []);
    $response->assertSessionHasErrors(['phone', 'location']);

    // Request checkout with details -> should succeed
    $response = $this->actingAs($buyer)->post(route('checkout'), [
        'phone' => '+237 699 000 000',
        'location' => 'Douala, Cameroun',
    ]);

    $response->assertRedirect(route('orders.index'));
    $response->assertSessionHas('status');

    // Assert database has order with phone and location
    $this->assertDatabaseHas('orders', [
        'buyer_id' => $buyer->id,
        'product_id' => $product->id,
        'amount' => 5000,
        'phone' => '+237 699 000 000',
        'location' => 'Douala, Cameroun',
        'status' => 'funds_held',
    ]);

    // Check balances
    $buyer->refresh();
    expect((float)$buyer->balance)->toEqual(95000);
});

it('allows vendor to ship and deliver order, and buyer to release funds', function () {
    $buyer = User::factory()->create(['balance' => 100000]);
    $buyer->assignRole('buyer');

    $vendor = User::factory()->create(['balance' => 0]);
    $vendor->assignRole('vendor');

    $admin = User::factory()->create(['balance' => 0]);
    $admin->assignRole('admin');

    $category = Category::create([
        'name' => 'Test Category 2',
        'slug' => 'test-category-2'
    ]);

    $product = Product::create([
        'vendor_id' => $vendor->id,
        'category_id' => $category->id,
        'title' => 'Test Product 2',
        'description' => 'Test Product Description 2',
        'price' => 10000,
        'condition' => 'new',
        'status' => 'available',
        'location' => 'Douala',
        'is_in_stock' => true,
    ]);

    CartItem::create([
        'user_id' => $buyer->id,
        'product_id' => $product->id,
        'quantity' => 1,
    ]);

    // Place Order
    $this->actingAs($buyer)->post(route('checkout'), [
        'phone' => '+237 699 000 000',
        'location' => 'Douala, Cameroun',
    ]);

    $order = Order::where('buyer_id', $buyer->id)->first();
    expect($order->status)->toEqual('funds_held');

    // Vendor ships order
    $response = $this->actingAs($vendor)->post(route('orders.ship', $order), [
        'tracking_number' => 'TRK12345',
    ]);
    $response->assertSessionHas('status');
    $order->refresh();
    expect($order->status)->toEqual('shipped');
    expect($order->tracking_number)->toEqual('TRK12345');

    // Vendor delivers order
    $response = $this->actingAs($vendor)->post(route('orders.deliver', $order));
    $response->assertSessionHas('status');
    $order->refresh();
    expect($order->status)->toEqual('delivered');

    // Buyer confirms receipt and releases funds
    $response = $this->actingAs($buyer)->post(route('orders.complete', $order), [
        'release_code' => $order->release_code,
    ]);
    $response->assertSessionHas('status');

    $order->refresh();
    expect($order->status)->toEqual('completed');

    // Vendor should receive 95% of funds (10000 * 0.95 = 9500)
    $vendor->refresh();
    expect((float)$vendor->balance)->toEqual(9500);

    // Admin should receive 5% tax (500)
    $admin->refresh();
    expect((float)$admin->balance)->toEqual(500);
});
