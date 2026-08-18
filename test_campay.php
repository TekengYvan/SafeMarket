<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$campay = app(\App\Services\CampayService::class);
$user = \App\Models\User::first() ?? \App\Models\User::factory()->create();

echo "Testing Campay Service Deposit for user: " . $user->email . "\n";

try {
    $tx = $campay->deposit($user, 5000, '237699000000', 'momo');
    echo "Transaction ID: " . $tx->id . "\n";
    echo "Status: " . $tx->status . "\n";
    echo "Reference: " . $tx->reference . "\n";
    echo "Provider Ref: " . ($tx->provider_reference ?? 'none') . "\n";
    echo "Description: " . $tx->description . "\n";
} catch (\Throwable $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
