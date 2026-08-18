<?php

namespace App\Livewire\Vendor;

use Livewire\Component;
use App\Models\Transaction;
use App\Services\CampayService;
use Illuminate\Support\Facades\Auth;

class WalletManager extends Component
{
    // Deposit properties
    public $depositAmount = 10000;
    public $depositPhone;
    public $depositMethod = 'momo';
    public $showDepositModal = false;
    public $activePendingTxId = null;

    // Withdraw properties
    public $withdrawAmount = 5000;
    public $withdrawPhone;
    public $withdrawMethod = 'momo';
    public $showWithdrawModal = false;

    public function mount()
    {
        $this->depositPhone = Auth::user()->phone_number ?? '+237 ';
        $this->withdrawPhone = Auth::user()->phone_number ?? '+237 ';
        
        $pending = Transaction::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->where('type', 'deposit')
            ->latest()
            ->first();
            
        if ($pending) {
            $this->activePendingTxId = $pending->id;
        }
    }

    public function deposit(CampayService $campayService)
    {
        $this->validate([
            'depositAmount' => 'required|numeric|min:500',
            'depositPhone'  => 'required|string|min:9',
            'depositMethod' => 'required|in:momo,om',
        ]);

        try {
            $tx = $campayService->deposit(Auth::user(), (float) $this->depositAmount, $this->depositPhone, $this->depositMethod);
            $this->showDepositModal = false;
            $this->dispatch('close-deposit-modal');

            if ($tx->status === 'successful') {
                $this->activePendingTxId = null;
                session()->flash('status', "✅ Recharge de " . number_format($this->depositAmount, 0, ',', ' ') . " FCFA créditée avec succès sur votre wallet !");
            } elseif ($tx->status === 'pending') {
                $this->activePendingTxId = $tx->id;
                session()->flash('status', "📱 Demande envoyée à votre téléphone ! Veuillez confirmer le paiement de " . number_format($this->depositAmount, 0, ',', ' ') . " FCFA sur votre téléphone ({$this->depositPhone}) en validant avec votre code secret Mobile Money.");
            } else {
                $this->activePendingTxId = null;
                session()->flash('error', $tx->description ?? "La recharge a échoué. Veuillez vérifier votre numéro et réessayer.");
            }
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    /**
     * Poll all pending transactions for the current user and sync status with Campay.
     */
    public function checkPendingTransactions(CampayService $campayService)
    {
        $pendingTransactions = Transaction::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->where('created_at', '>=', now()->subHours(1))
            ->get();

        if ($pendingTransactions->isEmpty()) {
            $this->activePendingTxId = null;
            return;
        }

        foreach ($pendingTransactions as $tx) {
            $updated = $campayService->checkTransactionStatus($tx);
            if ($updated->status === 'successful') {
                if ($this->activePendingTxId === $tx->id) {
                    $this->activePendingTxId = null;
                }
                session()->flash('status', "🎉 Paiement validé sur le téléphone ! Votre wallet a été crédité de " . number_format($updated->amount, 0, ',', ' ') . " FCFA.");
            } elseif ($updated->status === 'failed') {
                if ($this->activePendingTxId === $tx->id) {
                    $this->activePendingTxId = null;
                }
                session()->flash('error', "❌ Paiement refusé ou annulé sur votre téléphone.");
            }
        }
    }

    /**
     * Poll a specific pending transaction to check if it has been confirmed.
     */
    public function checkStatus(int $txId, CampayService $campayService)
    {
        $transaction = Transaction::where('id', $txId)->where('user_id', Auth::id())->first();
        if (! $transaction || $transaction->status !== 'pending') {
            return;
        }

        $updated = $campayService->checkTransactionStatus($transaction);
        if ($updated->status === 'successful') {
            $this->activePendingTxId = null;
            session()->flash('status', "✅ Paiement confirmé sur votre téléphone et solde crédité de " . number_format($updated->amount, 0, ',', ' ') . " FCFA !");
        } elseif ($updated->status === 'failed') {
            $this->activePendingTxId = null;
            session()->flash('error', "❌ Paiement annulé ou expiré : " . $updated->description);
        } else {
            session()->flash('status', "⏳ Demande toujours en attente sur votre téléphone. Veuillez vérifier l'écran de votre téléphone et entrer votre code secret.");
        }
    }

    public function dismissPending()
    {
        $this->activePendingTxId = null;
    }

    public function withdraw(CampayService $campayService)
    {
        $user = Auth::user();
        $this->validate([
            'withdrawAmount' => 'required|numeric|min:1000|max:' . $user->balance,
            'withdrawPhone' => 'required|string|min:9',
            'withdrawMethod' => 'required|in:momo,om',
        ]);

        try {
            $tx = $campayService->withdraw($user, (float) $this->withdrawAmount, $this->withdrawPhone, $this->withdrawMethod);
            $this->showWithdrawModal = false;
            $this->dispatch('close-withdraw-modal');
            session()->flash('status', "Retrait de " . number_format($this->withdrawAmount, 2) . " FCFA initié avec succès vers " . strtoupper($this->withdrawMethod) . " ({$this->withdrawPhone}) !");
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->latest()
            ->take(15)
            ->get();

        $pendingTransaction = $this->activePendingTxId 
            ? $transactions->firstWhere('id', $this->activePendingTxId) 
            : $transactions->firstWhere('status', 'pending');

        $hasPending = $transactions->where('status', 'pending')->isNotEmpty();

        return view('livewire.vendor.wallet-manager', compact('transactions', 'pendingTransaction', 'hasPending'));
    }
}
