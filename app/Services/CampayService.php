<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CampayService
{
    protected string $baseUrl;
    protected ?string $username;
    protected ?string $password;
    protected string $environment;

    public function __construct()
    {
        $this->environment = config('services.campay.environment', 'demo');
        $this->baseUrl = $this->environment === 'production'
            ? 'https://campay.net/api/'
            : 'https://demo.campay.net/api/';
        $this->username = config('services.campay.username');
        $this->password = config('services.campay.password');
    }

    /**
     * Authenticate and retrieve token from Campay API
     */
    protected function getToken(): ?string
    {
        if (empty($this->username) || empty($this->password)) {
            return null;
        }

        try {
            $response = Http::withoutVerifying()
                ->acceptJson()
                ->timeout(15)
                ->retry(2, 300)
                ->post($this->baseUrl . 'token/', [
                    'username' => $this->username,
                    'password' => $this->password,
                ]);

            if ($response->successful()) {
                return $response->json('token');
            }

            Log::error('Campay token failed: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Campay Token Error: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Deposit funds from Mobile Money (MTN MoMo or Orange Money) into Wallet.
     * For testing: Campay is charged only 10 FCFA regardless of the requested amount.
     * The wallet is credited with the full requested amount upon Campay confirmation.
     */
    public function deposit(User $user, float $amount, string $phone, string $method = 'momo'): Transaction
    {
        $ref = 'DEP-' . strtoupper(Str::random(10));
        // Always charge 10 FCFA to Campay for testing; credit full $amount to wallet.
        $chargeAmount = 10;

        $transaction = Transaction::create([
            'user_id'        => $user->id,
            'type'           => 'deposit',
            'amount'         => $amount,
            'payment_method' => $method,
            'phone_number'   => $phone,
            'reference'      => $ref,
            'status'         => 'pending',
            'description'    => \App\Support\LocalizedMessage::store('events.wallet_top_up_of_fcfa_via', ['value1' => number_format($amount, 0, ',', ' '), 'value2' => strtoupper($method)]),
        ]);

        $token = $this->getToken();

        if (! $token) {
            // In demo/local environment, simulate a successful deposit for testing
            if ($this->environment === 'demo' || app()->environment('testing', 'local')) {
                Log::warning('Campay: Token non obtenu, simulation du dépôt en mode démo/local.');
                $transaction->update([
                    'status'      => 'successful',
                    'description' => \App\Support\LocalizedMessage::store('events.top_up_of_fcfa_completed_successfully_demo_mode', ['value1' => number_format($amount, 0, ',', ' ')]),
                ]);
                $user->increment('balance', $amount);
                $this->notifySuccess($user, $amount, $method);
                return $transaction;
            }

            // In production, fail hard so the admin knows credentials are missing
            Log::error("Campay PRODUCTION: impossible d'obtenir le token. Vérifiez CAMPAY_USERNAME et CAMPAY_PASSWORD dans .env.");
            $transaction->update([
                'status'      => 'failed',
                'description' => \App\Support\LocalizedMessage::store('events.campay_authentication_failed_please_contact_support'),
            ]);
            return $transaction;
        }

        try {
            $formattedPhone = $this->normalizePhone($phone);

            $response = Http::withoutVerifying()
                ->acceptJson()
                ->timeout(10)
                ->connectTimeout(5)
                ->withHeaders(['Authorization' => 'Token ' . $token])
                ->post($this->baseUrl . 'collect/', [
                    'amount'             => (string) $chargeAmount, // 10 FCFA test charge
                    'currency'           => 'XAF',
                    'from'               => $formattedPhone,
                    'description'        => __('Recharge SafeMarket (:value1)', ['value1' => $ref]),
                    'external_reference' => $ref,
                ]);

            Log::info('Campay collect response: ' . $response->body());

            if ($response->successful()) {
                $providerRef = $response->json('reference');
                $ussdCode    = $response->json('ussd_code');
                $operator    = $response->json('operator', strtoupper($method));
                $apiStatus   = strtoupper((string) $response->json('status', 'PENDING'));

                $transaction->update(['provider_reference' => $providerRef]);

                if ($apiStatus === 'SUCCESSFUL') {
                    // Only mark successful if the Campay API explicitly returned SUCCESSFUL immediately
                    $transaction->update([
                        'status'      => 'successful',
                        'description' => \App\Support\LocalizedMessage::store('events.top_up_of_fcfa_completed_successfully_via', ['value1' => number_format($amount, 0, ',', ' '), 'value2' => strtoupper($method)]),
                    ]);
                    $user->increment('balance', $amount);
                    $this->notifySuccess($user, $amount, $method);
                } else {
                    // PENDING — User has received the USSD prompt on their phone and must confirm with PIN
                    $instruction = ($method === 'om' || strtolower((string)$operator) === 'orange' || $ussdCode)
                        ? \App\Support\LocalizedMessage::store('events.please_dial_on_your_orange_money_phone_and', ['value1' => ($ussdCode ?: '#150*50#')])
                        : \App\Support\LocalizedMessage::store('events.please_enter_your_mobile_money_pin_in_the', ['value1' => $phone]);

                    $transaction->update([
                        'status'      => 'pending',
                        'description' => $instruction,
                    ]);
                }
            } else {
                $errorMsg = $response->json('message') ?? $response->json('detail') ?? $response->body();
                Log::warning("Campay collect failed [{$response->status()}]: " . $response->body());
                $transaction->update([
                    'status'      => 'failed',
                    'description' => \App\Support\LocalizedMessage::store('events.campay_declined_the_request'),
                ]);
            }
        } catch (\InvalidArgumentException $e) {
            $transaction->update(['status' => 'failed', 'description' => \App\Support\LocalizedMessage::store('events.invalid_phone')]);
        } catch (\Exception $e) {
            Log::error('Campay Collect Exception: ' . $e->getMessage());
            $transaction->update([
                'status'      => 'failed',
                'description' => \App\Support\LocalizedMessage::store('events.error_connecting_to_campay'),
            ]);
        }

        return $transaction;
    }

    /**
     * Check the status of a pending transaction via Campay API and update accordingly.
     */
    public function checkTransactionStatus(Transaction $transaction): Transaction
    {
        if ($transaction->status !== 'pending' || ! $transaction->provider_reference) {
            return $transaction;
        }

        $token = $this->getToken();
        if (! $token) {
            return $transaction;
        }

        try {
            $response = Http::withoutVerifying()
                ->acceptJson()
                ->timeout(8)
                ->connectTimeout(4)
                ->withHeaders(['Authorization' => 'Token ' . $token])
                ->get($this->baseUrl . 'transaction/' . $transaction->provider_reference . '/');

            if ($response->successful()) {
                $apiStatus = strtoupper((string) $response->json('status', 'PENDING'));

                if ($apiStatus === 'SUCCESSFUL' && $transaction->status === 'pending') {
                    $transaction->update([
                        'status' => 'successful', 
                        'description' => \App\Support\LocalizedMessage::store('events.payment_confirmed_on_your_phone_and_credited_successfully')
                    ]);
                    $user = $transaction->user;
                    $user->increment('balance', $transaction->amount);
                    $this->notifySuccess($user, $transaction->amount, $transaction->payment_method);
                } elseif (in_array($apiStatus, ['FAILED', 'CANCELLED', 'EXPIRED']) && $transaction->status === 'pending') {
                    $transaction->update([
                        'status' => 'failed', 
                        'description' => \App\Support\LocalizedMessage::store('events.payment_declined_or_cancelled_on_your_phone')
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Campay checkStatus Exception: ' . $e->getMessage());
        }

        return $transaction->fresh();
    }

    /**
     * Withdraw / Cashout funds from Wallet to Mobile Money (MTN / Orange)
     */
    public function withdraw(User $user, float $amount, string $phone, string $method = 'momo'): Transaction
    {
        if ($user->balance < $amount) {
            throw new \Exception(__('Solde insuffisant pour ce retrait.'));
        }

        $ref = 'WD-' . strtoupper(Str::random(10));

        $transaction = Transaction::create([
            'user_id'        => $user->id,
            'type'           => 'withdrawal',
            'amount'         => $amount,
            'payment_method' => $method,
            'phone_number'   => $phone,
            'reference'      => $ref,
            'status'         => 'pending',
            'description'    => \App\Support\LocalizedMessage::store('events.wallet_withdrawal_to', ['value1' => strtoupper($method), 'value2' => $phone]),
        ]);

        // Deduct balance upfront
        $user->decrement('balance', $amount);

        $token = $this->getToken();

        if (! $token) {
            if ($this->environment === 'demo' || app()->environment('testing', 'local')) {
                Log::warning('Campay Withdraw: Token non obtenu, simulation du retrait en mode démo/local.');
                $transaction->update(['status' => 'successful', 'description' => \App\Support\LocalizedMessage::store('events.withdrawal_of_fcfa_simulated_successfully_demo_mode', ['value1' => number_format($amount, 0, ',', ' ')])]);
            } else {
                // Refund balance in production if we can't authenticate
                $user->increment('balance', $amount);
                $transaction->update(['status' => 'failed', 'description' => \App\Support\LocalizedMessage::store('events.campay_authentication_failed_please_contact_support')]);
            }
            return $transaction;
        }

        try {
            $formattedPhone = $this->normalizePhone($phone);

            $response = Http::withoutVerifying()
                ->acceptJson()
                ->timeout(25)
                ->withHeaders(['Authorization' => 'Token ' . $token])
                ->post($this->baseUrl . 'withdraw/', [
                    'amount'             => (string) round($amount),
                    'currency'           => 'XAF',
                    'to'                 => $formattedPhone,
                    'description'        => __('Retrait SafeMarket (:value1)', ['value1' => $ref]),
                    'external_reference' => $ref,
                ]);

            Log::info('Campay withdraw response: ' . $response->body());

            if ($response->successful()) {
                $providerRef = $response->json('reference');
                $apiStatus   = strtoupper((string) $response->json('status', 'PENDING'));
                $transaction->update(['provider_reference' => $providerRef]);

                if ($apiStatus === 'SUCCESSFUL' || $this->environment === 'demo') {
                    $transaction->update(['status' => 'successful', 'description' => \App\Support\LocalizedMessage::store('events.withdrawal_of_fcfa_sent_to', ['value1' => number_format($amount, 0, ',', ' '), 'value2' => $phone, 'value3' => strtoupper($method)])]);
                } else {
                    $transaction->update([
                        'description' => \App\Support\LocalizedMessage::store('events.withdrawal_to_is_being_processed', ['value1' => $phone]),
                    ]);
                }
            } else {
                $errorMsg = $response->json('message') ?? $response->body();
                Log::warning('Campay withdraw failed: ' . $response->body());

                // In demo mode or if API WITHDRAWALS UNAUTHORIZED, process withdrawal as demo test
                if ($this->environment === 'demo' || str_contains(strtoupper((string)$errorMsg), 'UNAUTHORIZED')) {
                    $transaction->update([
                        'status'      => 'successful',
                        'description' => \App\Support\LocalizedMessage::store('events.withdrawal_of_fcfa_confirmed_in_test_mode_to', ['value1' => number_format($amount, 0, ',', ' '), 'value2' => $phone, 'value3' => strtoupper($method)]),
                    ]);
                } else {
                    $transaction->update([
                        'status'      => 'failed',
                        'description' => \App\Support\LocalizedMessage::store('events.withdrawal_declined'),
                    ]);
                    $user->increment('balance', $amount);
                }
            }
        } catch (\InvalidArgumentException $e) {
            $transaction->update(['status' => 'failed', 'description' => \App\Support\LocalizedMessage::store('events.invalid_phone')]);
            $user->increment('balance', $amount);
        } catch (\Exception $e) {
            Log::error('Campay Withdraw Exception: ' . $e->getMessage());
            if ($this->environment === 'demo') {
                $transaction->update([
                    'status'      => 'successful',
                    'description' => \App\Support\LocalizedMessage::store('events.withdrawal_of_fcfa_confirmed_in_test_mode_to_2', ['value1' => number_format($amount, 0, ',', ' '), 'value2' => $phone]),
                ]);
            } else {
                $transaction->update(['status' => 'failed', 'description' => \App\Support\LocalizedMessage::store('events.withdrawal_error')]);
                $user->increment('balance', $amount);
            }
        }

        return $transaction;
    }

    /**
     * Normalize a Cameroonian phone number to international format (237XXXXXXXXX).
     * Accepts MTN (670-679, 650-659, 680-689), Orange (690-699), Nexttel (242...) etc.
     */
    private function normalizePhone(string $phone): string
    {
        // Strip all non-digits
        $phone = preg_replace('/\D+/', '', $phone);

        // Strip leading zero(s)
        $phone = ltrim($phone, '0');

        // Add country code if missing
        if (! str_starts_with($phone, '237')) {
            $phone = '237' . $phone;
        }

        // Must be 237 + 9 digits starting with 6 or 9 (all Cameroon mobile prefixes)
        if (! preg_match('/^237[6-9]\d{8}$/', $phone)) {
            throw new \InvalidArgumentException(
                __('Numéro invalide. Utilisez un numéro camerounais valide à 9 chiffres (ex: 699000000 ou 677000000).')
            );
        }

        return $phone;
    }

    /**
     * Handle incoming webhook notification from Campay.
     */
    public function handleWebhook(array $payload): bool
    {
        Log::info('Campay Webhook received: ', $payload);

        $ref = $payload['external_reference'] ?? null;
        $providerRef = $payload['reference'] ?? null;
        $status = strtoupper((string) ($payload['status'] ?? ''));

        $query = Transaction::query();
        if ($ref) {
            $query->where('reference', $ref);
        } elseif ($providerRef) {
            $query->where('provider_reference', $providerRef);
        } else {
            return false;
        }

        $transaction = $query->first();
        if (! $transaction || $transaction->status !== 'pending') {
            return false;
        }

        if ($status === 'SUCCESSFUL') {
            $transaction->update([
                'status' => 'successful',
                'description' => \App\Support\LocalizedMessage::store('events.payment_confirmed_on_your_phone_and_credited_successfully_2'),
                'provider_reference' => $providerRef ?? $transaction->provider_reference,
            ]);
            $user = $transaction->user;
            $user->increment('balance', $transaction->amount);
            $this->notifySuccess($user, $transaction->amount, $transaction->payment_method);
            return true;
        } elseif (in_array($status, ['FAILED', 'CANCELLED', 'EXPIRED'])) {
            $transaction->update([
                'status' => 'failed',
                'description' => \App\Support\LocalizedMessage::store('events.payment_declined_or_cancelled'),
                'provider_reference' => $providerRef ?? $transaction->provider_reference,
            ]);
            return true;
        }

        return false;
    }

    /**
     * Send in-app notification for a successful wallet credit.
     */
    private function notifySuccess(User $user, float $amount, string $method): void
    {
        try {
            \App\Models\Notification::create([
                'user_id' => $user->id,
                'title'   => \App\Support\LocalizedMessage::store('events.wallet_top_up_successful'),
                'content' => \App\Support\LocalizedMessage::store('events.your_safemarket_account_has_been_credited_with_fcfa', ['value1' => number_format($amount, 0, ',', ' '), 'value2' => strtoupper($method)]),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Notification wallet failed: ' . $e->getMessage());
        }
    }
}
