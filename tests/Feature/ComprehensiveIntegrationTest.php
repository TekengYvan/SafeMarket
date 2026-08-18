<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\Negotiation;
use App\Models\Message;
use App\Models\Transaction;
use App\Services\CampayService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ComprehensiveIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'vendor']);
        Role::firstOrCreate(['name' => 'buyer']);
    }

    public function test_product_manager_can_create_and_edit_products(): void
    {
        Storage::fake('public');

        $vendor = User::factory()->create(['kyc_status' => 'verified']);
        $vendor->assignRole('vendor');

        $category = Category::create(['name' => 'Electronics', 'slug' => 'electronics']);

        $image = UploadedFile::fake()->create('test_product.jpg', 10, 'image/jpeg');

        Livewire::actingAs($vendor)
            ->test(\App\Livewire\Vendor\ProductManager::class)
            ->call('openCreateModal')
            ->assertSet('showFormModal', true)
            ->set('title', 'PlayStation 5')
            ->set('description', 'Console de jeux ultra performante')
            ->set('price', 350000)
            ->set('category_id', $category->id)
            ->set('condition', 'new')
            ->set('location', 'Douala')
            ->set('images', [$image])
            ->call('store')
            ->assertHasNoErrors()
            ->assertSet('showFormModal', false);

        $this->assertDatabaseHas('products', [
            'title' => 'PlayStation 5',
            'vendor_id' => $vendor->id,
            'price' => 350000,
        ]);

        $product = Product::where('title', 'PlayStation 5')->first();

        Livewire::actingAs($vendor)
            ->test(\App\Livewire\Vendor\ProductManager::class)
            ->call('edit', $product->id)
            ->assertSet('showFormModal', true)
            ->assertSet('isEditing', true)
            ->set('title', 'PlayStation 5 Slim')
            ->call('update')
            ->assertHasNoErrors()
            ->assertSet('showFormModal', false);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'title' => 'PlayStation 5 Slim',
        ]);
    }

    public function test_kyc_submission_and_admin_verification(): void
    {
        Storage::fake('public');

        $user = User::factory()->create(['kyc_status' => 'pending']);
        $idCard = UploadedFile::fake()->create('cni.jpg', 10, 'image/jpeg');

        Livewire::actingAs($user)
            ->test(\App\Livewire\Profile\KycSubmission::class)
            ->set('idCard', $idCard)
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('status', 'pending');

        $user->refresh();
        $this->assertEquals('pending', $user->kyc_status);
        $this->assertNotNull($user->id_card_photo);

        $admin = User::factory()->create(['is_admin' => true]);
        $admin->assignRole('admin');

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\KycVerification::class)
            ->call('verify', $user->id, 'verified')
            ->assertHasNoErrors();

        $user->refresh();
        $this->assertEquals('verified', $user->kyc_status);
        $this->assertTrue($user->hasRole('vendor'));
    }

    public function test_dispute_opening_and_admin_resolution(): void
    {
        $buyer = User::factory()->create(['balance' => 0]);
        $vendor = User::factory()->create(['balance' => 0]);
        $vendor->assignRole('vendor');

        $category = Category::create(['name' => 'Fashion', 'slug' => 'fashion']);
        $product = Product::create([
            'vendor_id' => $vendor->id,
            'category_id' => $category->id,
            'title' => 'Sneakers',
            'description' => 'Cool shoes',
            'price' => 50000,
            'condition' => 'new',
            'status' => 'available',
            'location' => 'Yaoundé',
            'is_in_stock' => true,
        ]);

        $order = Order::create([
            'buyer_id' => $buyer->id,
            'product_id' => $product->id,
            'amount' => 50000,
            'phone' => '699000000',
            'location' => 'Yaoundé',
            'status' => 'funds_held',
            'release_code' => 'TEST1234',
        ]);

        Livewire::actingAs($buyer)
            ->test(\App\Livewire\Marketplace\DisputeButton::class, ['order' => $order])
            ->set('reason', 'Le colis reçu ne contient pas la bonne pointure')
            ->call('openDispute')
            ->assertHasNoErrors();

        $order->refresh();
        $this->assertTrue((bool)$order->is_disputed);
        $this->assertEquals('pending', $order->dispute_status);

        $admin = User::factory()->create(['is_admin' => true]);
        $admin->assignRole('admin');

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\DisputeManager::class)
            ->call('resolve', $order->id, 'buyer')
            ->assertHasNoErrors();

        $order->refresh();
        $buyer->refresh();
        $this->assertEquals('resolved_to_buyer', $order->dispute_status);
        $this->assertEquals('cancelled', $order->status);
        $this->assertEquals(50000, (float)$buyer->balance);
    }

    public function test_negotiation_chat_messages_are_saved_and_rendered(): void
    {
        $buyer = User::factory()->create();
        $vendor = User::factory()->create();
        $vendor->assignRole('vendor');

        $category = Category::create(['name' => 'Phones', 'slug' => 'phones']);
        $product = Product::create([
            'vendor_id' => $vendor->id,
            'category_id' => $category->id,
            'title' => 'iPhone 12',
            'description' => 'Good phone',
            'price' => 200000,
            'condition' => 'good',
            'status' => 'available',
            'location' => 'Douala',
            'is_in_stock' => true,
        ]);

        $negotiation = Negotiation::create([
            'product_id' => $product->id,
            'buyer_id' => $buyer->id,
            'seller_id' => $vendor->id,
            'original_price' => 200000,
            'proposed_price' => 180000,
            'message' => 'Je propose 180 000 FCFA comptant.',
            'status' => 'pending',
        ]);

        Livewire::actingAs($buyer)
            ->test(\App\Livewire\NegotiationChat::class, ['negotiation' => $negotiation])
            ->assertSee('Je propose 180 000 FCFA comptant.')
            ->set('newMessage', 'Est-ce que le chargeur original est fourni ?')
            ->call('sendMessage')
            ->assertHasNoErrors()
            ->assertSee('Est-ce que le chargeur original est fourni ?');

        $this->assertDatabaseHas('messages', [
            'negotiation_id' => $negotiation->id,
            'content' => 'Est-ce que le chargeur original est fourni ?',
        ]);

        Livewire::actingAs($vendor)
            ->test(\App\Livewire\NegotiationChat::class, ['negotiation' => $negotiation])
            ->assertSee('Est-ce que le chargeur original est fourni ?')
            ->set('newMessage', 'Oui, boîte complète et câble.')
            ->call('sendMessage')
            ->assertHasNoErrors()
            ->call('updateStatus', 'accepted');

        $negotiation->refresh();
        $this->assertEquals('accepted', $negotiation->status);
    }

    public function test_campay_wallet_deposit_and_withdraw(): void
    {
        \Illuminate\Support\Facades\Http::fake([
            '*/token/' => \Illuminate\Support\Facades\Http::response(['token' => 'mock_token_123'], 200),
            '*/collect/' => \Illuminate\Support\Facades\Http::response([
                'reference' => 'CAMPAY-MOCK-REF',
                'status' => 'PENDING',
                'operator' => 'MTN',
            ], 200),
            '*/transaction/CAMPAY-MOCK-REF/' => \Illuminate\Support\Facades\Http::response([
                'reference' => 'CAMPAY-MOCK-REF',
                'status' => 'SUCCESSFUL',
                'amount' => 10,
                'currency' => 'XAF',
            ], 200),
            '*/withdraw/' => \Illuminate\Support\Facades\Http::response([
                'reference' => 'CAMPAY-WD-MOCK-REF',
                'status' => 'SUCCESSFUL',
            ], 200),
        ]);

        $user = User::factory()->create(['balance' => 0]);

        $wallet = Livewire::actingAs($user)
            ->test(\App\Livewire\Vendor\WalletManager::class)
            ->set('depositAmount', 15000)
            ->set('depositPhone', '699112233')
            ->set('depositMethod', 'momo')
            ->call('deposit', app(CampayService::class))
            ->assertHasNoErrors();

        // Transaction is created in pending status awaiting confirmation
        $tx = \App\Models\Transaction::where('user_id', $user->id)->where('type', 'deposit')->first();
        $this->assertNotNull($tx);
        $this->assertEquals('pending', $tx->status);

        // Simulated phone confirmation: checking status
        $wallet->call('checkPendingTransactions', app(CampayService::class));

        $user->refresh();
        $this->assertEquals(15000, (float)$user->balance);
        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'type' => 'deposit',
            'amount' => 15000,
            'status' => 'successful',
        ]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\Vendor\WalletManager::class)
            ->set('withdrawAmount', 5000)
            ->set('withdrawPhone', '677998877')
            ->set('withdrawMethod', 'om')
            ->call('withdraw', app(CampayService::class))
            ->assertHasNoErrors();

        $user->refresh();
        $this->assertEquals(10000, (float)$user->balance);
        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'type' => 'withdrawal',
            'amount' => 5000,
        ]);
    }

    public function test_campay_webhook_processing(): void
    {
        $user = User::factory()->create(['balance' => 0]);
        $tx = \App\Models\Transaction::create([
            'user_id' => $user->id,
            'type' => 'deposit',
            'amount' => 20000,
            'payment_method' => 'momo',
            'phone_number' => '699112233',
            'reference' => 'DEP-TEST-HOOK-1',
            'provider_reference' => 'CAMPAY-HOOK-REF',
            'status' => 'pending',
        ]);

        $response = $this->postJson('/api/campay/webhook', [
            'reference' => 'CAMPAY-HOOK-REF',
            'external_reference' => 'DEP-TEST-HOOK-1',
            'status' => 'SUCCESSFUL',
            'amount' => 10,
            'currency' => 'XAF',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success', 'processed' => true]);

        $user->refresh();
        $this->assertEquals(20000, (float)$user->balance);
        $this->assertEquals('successful', $tx->fresh()->status);
    }
}
