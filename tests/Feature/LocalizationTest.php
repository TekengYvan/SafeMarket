<?php

use App\Http\Middleware\SetLocale;
use App\Livewire\CartComponent;
use App\Livewire\NotificationBell;
use App\Livewire\Vendor\WalletManager;
use App\Models\Category;
use App\Models\Notification;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Services\CampayService;
use App\Support\LocalizedMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Livewire\Livewire;

beforeEach(function () {
    Http::preventStrayRequests();
    config(['services.campay.environment' => 'production', 'services.campay.username' => 'test', 'services.campay.password' => 'test']);
});

it('keeps the selected language through navigation and authentication forms', function () {
    $this->from('/login')->get('/lang/fr')->assertRedirect('/login')->assertSessionHas('locale', 'fr');
    $this->get('/login')->assertOk()->assertSee('lang="fr"', false)->assertSee('Français');
    $this->from('/login')->get('/lang/en')->assertRedirect('/login')->assertSessionHas('locale', 'en');
    $this->get('/login')->assertOk()->assertSee('lang="en"', false);
    $this->get('/lang/de')->assertSessionHas('locale', 'en');
});

it('resets an invalid session locale and negotiates the API language', function () {
    $request = Request::create('/');
    $session = app('session.store');
    $session->put('locale', '../../invalid');
    $request->setLaravelSession($session);
    app()->setLocale('fr');
    (new SetLocale)->handle($request, fn () => response('ok'));
    expect(app()->getLocale())->toBe('en');

    $api = Request::create('/api/products', server: ['HTTP_ACCEPT_LANGUAGE' => 'fr-FR,fr;q=0.9,en;q=0.8']);
    (new SetLocale)->handle($api, fn () => response('ok'));
    expect(app()->getLocale())->toBe('fr');
});

it('translates validation fields and authentication errors', function (string $locale) {
    app()->setLocale($locale);
    $validator = Validator::make(['depositAmount' => 1, 'depositPhone' => '123'], [
        'depositAmount' => 'required|numeric|min:500',
        'depositPhone' => 'required|string|min:9',
    ]);
    $errors = $validator->errors()->all();
    expect(implode(' ', $errors))
        ->toContain($locale === 'fr' ? 'montant de la recharge' : 'top-up amount')
        ->toContain($locale === 'fr' ? 'numéro Mobile Money' : 'Mobile Money phone number')
        ->not->toContain('validation.');
    expect(__('auth.failed'))->not->toBe('auth.failed');
    expect(__('passwords.sent'))->not->toBe('passwords.sent');
})->with(['en', 'fr']);

it('renders stored notifications in the reader language without changing user text', function () {
    $user = User::factory()->create();
    app()->setLocale('fr');
    $name = "D'Angelo <script>alert('x')</script>";
    $notification = Notification::create([
        'user_id' => $user->id,
        'title' => LocalizedMessage::store('events.new_negotiation_message'),
        'content' => LocalizedMessage::store('events.sent_you_a_message_about', ['value1' => $name, 'value2' => "L'appareil :value1"]),
    ]);
    app()->setLocale('en');
    expect($notification->fresh()->title)->toBe('New negotiation message');
    expect($notification->content)->toContain($name)->toContain("L'appareil :value1");
    $this->actingAs($user);
    $html = Livewire::test(NotificationBell::class)->html();
    expect($html)->toContain(e($name))->not->toContain("<script>alert('x')</script>");
    app()->setLocale('fr');
    expect($notification->content)->toContain('vous a envoyé un message');
});

it('translates existing plain-text notifications and transactions', function () {
    $notification = new Notification([
        'title' => 'Recharge Wallet réussie',
        'content' => 'Votre compte SafeMarket a été crédité de 10 000 FCFA via MOMO.',
    ]);
    $transaction = new Transaction(['description' => 'Veuillez composer le #150*50# sur votre téléphone Orange Money pour valider le débit avec votre code secret.']);
    app()->setLocale('en');
    expect($notification->title)->toBe('Wallet top-up successful');
    expect($notification->content)->toBe('Your SafeMarket account has been credited with 10 000 FCFA via MOMO.');
    expect($transaction->description)->toContain('Please dial #150*50#');
    app()->setLocale('fr');
    expect($transaction->description)->toStartWith('Veuillez composer');
    expect(LocalizedMessage::render('An unknown historical message'))->toBe('An unknown historical message');
});

it('translates negotiation statuses inside new and legacy notifications', function () {
    $raw = LocalizedMessage::store('events.the_offer_for_has_been', ['value1' => "L'ordinateur", 'value2' => LocalizedMessage::label('acceptée')]);
    app()->setLocale('en');
    expect(LocalizedMessage::render($raw))->toBe("The offer for 'L'ordinateur' has been accepted.");
    expect(LocalizedMessage::render("La proposition pour 'L'ordinateur' a été acceptée."))->toBe("The offer for 'L'ordinateur' has been accepted.");
    app()->setLocale('fr');
    expect(LocalizedMessage::render($raw))->toContain('a été acceptée.');
});

it('shows translated pending phone instructions and successful wallet confirmations', function (string $locale, string $method) {
    app()->setLocale($locale);
    session(['locale' => $locale]);
    $user = User::factory()->create(['balance' => 0]);
    Http::fake([
        '*/token/' => Http::response(['token' => 'fake-token']),
        '*/collect/' => Http::response(['reference' => 'provider-1', 'status' => 'PENDING', 'operator' => $method === 'om' ? 'orange' : 'mtn', 'ussd_code' => $method === 'om' ? '#150*50#' : null]),
        '*/transaction/*' => Http::response(['status' => 'SUCCESSFUL']),
    ]);
    $component = Livewire::actingAs($user)->test(WalletManager::class)
        ->set('depositAmount', 10000)->set('depositPhone', '699000000')->set('depositMethod', $method)
        ->call('deposit')->assertHasNoErrors()
        ->assertSee($locale === 'fr' ? 'Paiement en attente sur votre téléphone' : 'Payment awaiting confirmation on your phone')
        ->assertSee($locale === 'fr' ? 'Veuillez' : 'Please');
    $transaction = Transaction::firstOrFail();
    expect($transaction->status)->toBe('pending');
    if ($method === 'om') {
        expect($transaction->description)->toContain('#150*50#');
    } else {
        expect($transaction->description)->toContain('699000000');
    }
    $component->call('checkPendingTransactions')
        ->assertSee($locale === 'fr' ? 'Paiement validé sur le téléphone' : 'Payment confirmed on your phone');
    expect((float) $user->fresh()->balance)->toBe(10000.0);
    expect($transaction->fresh()->status)->toBe('successful');
    expect(Notification::firstOrFail()->title)->toBe($locale === 'fr' ? 'Recharge Wallet réussie' : 'Wallet top-up successful');
    expect($component->html())->not->toContain('@i18n:')->not->toContain('events.');
})->with(['en', 'fr'])->with(['momo', 'om']);

it('shows translated rejected payments without third-party language leakage', function (string $locale) {
    app()->setLocale($locale);
    session(['locale' => $locale]);
    $user = User::factory()->create(['balance' => 0]);
    Http::fake([
        '*/token/' => Http::response(['token' => 'fake-token']),
        '*/collect/' => Http::response(['message' => 'UNTRANSLATED_PROVIDER_ERROR'], 400),
    ]);
    Livewire::actingAs($user)->test(WalletManager::class)
        ->set('depositPhone', '699000000')->call('deposit')
        ->assertSee($locale === 'fr' ? 'CamPay a refusé' : 'CamPay declined')
        ->assertDontSee('UNTRANSLATED_PROVIDER_ERROR');
    expect((float) $user->fresh()->balance)->toBe(0.0);
    expect(Transaction::firstOrFail()->status)->toBe('failed');
})->with(['en', 'fr']);

it('renders webhook-created messages in either language', function () {
    $user = User::factory()->create(['balance' => 0]);
    $transaction = Transaction::create([
        'user_id' => $user->id, 'type' => 'deposit', 'amount' => 10000,
        'payment_method' => 'momo', 'reference' => 'DEP-LOCALE', 'status' => 'pending',
    ]);
    app()->setLocale('en');
    expect(app(CampayService::class)->handleWebhook(['external_reference' => 'DEP-LOCALE', 'status' => 'SUCCESSFUL']))->toBeTrue();
    app()->setLocale('fr');
    expect($transaction->fresh()->description)->toContain('Paiement confirmé');
    expect(Notification::firstOrFail()->content)->toContain('a été crédité de 10 000 FCFA');
    app()->setLocale('en');
    expect(Notification::firstOrFail()->content)->toContain('has been credited with 10 000 FCFA');
});

it('keeps checkout phone confirmation translated on Livewire updates', function (string $locale) {
    app()->setLocale($locale);
    session(['locale' => $locale]);
    $user = User::factory()->create(['balance' => 0]);
    $vendor = User::factory()->create();
    $category = Category::create(['name' => 'Test', 'slug' => 'test']);
    $product = Product::create([
        'vendor_id' => $vendor->id, 'category_id' => $category->id, 'title' => "L'appareil",
        'description' => 'User content', 'price' => 10000, 'condition' => 'new',
        'status' => 'available', 'location' => 'Douala', 'is_in_stock' => true,
    ]);
    $user->cartItems()->create(['product_id' => $product->id, 'quantity' => 1]);
    Http::fake([
        '*/token/' => Http::response(['token' => 'fake-token']),
        '*/collect/' => Http::response(['reference' => 'checkout-reference', 'status' => 'PENDING']),
        '*/transaction/*' => Http::response(['status' => 'FAILED']),
    ]);
    Livewire::actingAs($user)->test(CartComponent::class)
        ->set('phone', '699000000')->set('location', 'Douala')->set('paymentMethod', 'campay')
        ->call('submitCheckout')->assertSet('isWaitingPayment', true)
        ->assertSee($locale === 'fr' ? 'Validation Mobile Money Requise' : 'Mobile Money confirmation required')
        ->call('checkPaymentStatus')->assertSet('isWaitingPayment', false)
        ->assertSee($locale === 'fr' ? 'Le paiement Mobile Money a été refusé' : 'The Mobile Money payment was declined');
})->with(['en', 'fr']);

it('has matching translation keys and placeholders in both catalogs', function () {
    foreach (['json', 'events', 'labels'] as $group) {
        $en = $group === 'json' ? json_decode(file_get_contents(lang_path('en.json')), true, 512, JSON_THROW_ON_ERROR) : require lang_path("en/{$group}.php");
        $fr = $group === 'json' ? json_decode(file_get_contents(lang_path('fr.json')), true, 512, JSON_THROW_ON_ERROR) : require lang_path("fr/{$group}.php");
        expect(array_keys($en))->toBe(array_keys($fr));
        foreach ($en as $key => $value) {
            preg_match_all('/:[a-zA-Z][a-zA-Z0-9_]*/', $value, $enMatches);
            preg_match_all('/:[a-zA-Z][a-zA-Z0-9_]*/', $fr[$key], $frMatches);
            sort($enMatches[0]);
            sort($frMatches[0]);
            expect($frMatches[0], $key)->toBe($enMatches[0]);
        }
    }
});

it('resolves every literal application translation in both languages', function () {
    $files = array_merge(
        iterator_to_array(new RecursiveIteratorIterator(new RecursiveDirectoryIterator(app_path()))),
        iterator_to_array(new RecursiveIteratorIterator(new RecursiveDirectoryIterator(resource_path('views'))))
    );
    foreach ($files as $file) {
        if (! $file->isFile() || $file->getExtension() !== 'php') {
            continue;
        }
        preg_match_all('/(?:__|LocalizedMessage::store)\(\s*([\'"])((?:\\\\.|(?!\1).)*?)\1/s', file_get_contents($file->getPathname()), $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $key = str_replace(["\\'", '\\"', '\\\\'], ["'", '"', '\\'], $match[2]);
            foreach (['en', 'fr'] as $locale) {
                expect(trans()->hasForLocale($key, $locale), "{$file->getPathname()}: {$locale}: {$key}")->toBeTrue();
            }
        }
    }
});

it('keeps long translated transaction parameters intact', function () {
    $user = User::factory()->create();
    $title = str_repeat('é', 250);
    $transaction = Transaction::create([
        'user_id' => $user->id, 'type' => 'escrow_release', 'amount' => 9500,
        'payment_method' => 'wallet', 'reference' => 'LONG-TRANSLATION', 'status' => 'successful',
        'description' => LocalizedMessage::store('events.sale_payment_for_order', ['value1' => 123, 'value2' => $title]),
    ]);
    app()->setLocale('en');
    expect($transaction->fresh()->description)->toContain('Sale payment for order #123')->toContain($title);
    app()->setLocale('fr');
    expect($transaction->fresh()->description)->toContain('Paiement vente commande #123')->toContain($title);
});
