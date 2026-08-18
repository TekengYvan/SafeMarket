<div class="space-y-8" @if($hasPending) wire:poll.3s="checkPendingTransactions" @endif x-data="{ showDeposit: false, showWithdraw: false }" x-on:close-deposit-modal.window="showDeposit = false" x-on:close-withdraw-modal.window="showWithdraw = false">
    @if(session()->has('status'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-900/50 rounded-2xl text-emerald-800 dark:text-emerald-400 font-bold text-sm flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    @if(session()->has('error'))
        <div class="p-4 bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-900/50 rounded-2xl text-rose-800 dark:text-rose-400 font-bold text-sm flex items-center gap-2">
            <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Pending Payment Awaiting Phone Validation Card -->
    @if($pendingTransaction)
        <div class="p-6 bg-amber-500/10 dark:bg-amber-950/40 border-2 border-amber-500/50 rounded-3xl text-amber-900 dark:text-amber-200 shadow-xl relative overflow-hidden animate-pulse-slow">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-lg shadow-amber-500/30">
                        <svg class="w-6 h-6 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider bg-amber-500/20 text-amber-700 dark:text-amber-300 rounded-full border border-amber-500/30">
                                ⏳ {{ __('Paiement en attente sur votre téléphone') }}
                            </span>
                            <span class="text-xs text-amber-700/70 dark:text-amber-400 font-mono">{{ $pendingTransaction->reference }}</span>
                        </div>
                        <h4 class="text-base font-extrabold mt-1 text-gray-900 dark:text-white">
                            {{ __('Recharge de') }} {{ number_format($pendingTransaction->amount, 0, ',', ' ') }} FCFA ({{ strtoupper($pendingTransaction->payment_method) }})
                        </h4>
                        <p class="text-xs text-amber-800 dark:text-amber-300 mt-1 leading-relaxed max-w-2xl">
                            {{ $pendingTransaction->description }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2.5 w-full md:w-auto">
                    <button wire:click="checkStatus({{ $pendingTransaction->id }})" wire:loading.attr="disabled" class="flex-1 md:flex-none px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-extrabold rounded-2xl shadow-md transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 animate-spin" wire:loading wire:target="checkStatus({{ $pendingTransaction->id }})" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>{{ __('Vérifier la validation') }}</span>
                    </button>
                    <button wire:click="dismissPending" class="p-2.5 rounded-2xl text-amber-600 dark:text-amber-400 hover:bg-amber-500/10 transition" title="{{ __('Masquer') }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Wallet Balance Overview Card -->
    <div class="bg-gradient-to-br from-zinc-900 via-stone-900 to-zinc-950 p-8 rounded-3xl text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-12 -bottom-12 w-48 h-48 bg-red-600/20 rounded-full blur-3xl"></div>
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <span class="text-stone-400 text-xs font-bold uppercase tracking-widest block mb-1">{{ __('Solde Disponible (Wallet)') }}</span>
                <h2 class="text-4xl md:text-5xl font-black text-white tracking-tight">
                    {{ number_format(auth()->user()->balance, 2) }} <span class="text-red-500 text-2xl font-bold">FCFA</span>
                </h2>
                <p class="text-xs text-stone-400 mt-2 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    {{ __('Fonds sécurisés en séquestre SafeMarket ESCROW') }}
                </p>
            </div>

            <div class="flex flex-wrap gap-3 w-full md:w-auto">
                <button @click="showDeposit = true" class="flex-1 md:flex-none px-6 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-2xl shadow-lg shadow-emerald-900/30 transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    {{ __('Recharger (MoMo / OM)') }}
                </button>

                <button @click="showWithdraw = true" class="flex-1 md:flex-none px-6 py-3.5 bg-red-600 hover:bg-red-700 text-white font-bold text-sm rounded-2xl shadow-lg shadow-red-900/30 transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    {{ __('Retirer (MoMo / OM)') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Deposit Modal -->
    <div x-show="showDeposit" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6" x-cloak>
        <div class="fixed inset-0 bg-gray-950/70 backdrop-blur-md" @click="showDeposit = false"></div>
        <div class="relative bg-white dark:bg-gray-900 rounded-[2.5rem] border border-gray-150 dark:border-gray-800 p-6 sm:p-8 max-w-md w-full z-10 shadow-2xl">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100 dark:border-gray-800">
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-extrabold text-gray-900 dark:text-white">{{ __('Recharger mon Wallet') }}</h3>
                        <p class="text-xs text-gray-400">{{ __('Paiement Mobile Money instantané') }}</p>
                    </div>
                </div>
                <button type="button" @click="showDeposit = false" class="p-2 rounded-xl text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Notice Test 10 FCFA -->
            <div class="mb-5 p-3.5 bg-emerald-50/80 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-900/40 rounded-2xl text-emerald-800 dark:text-emerald-300 text-xs flex items-start gap-2.5">
                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <span class="font-extrabold block mb-0.5">{{ __('Test Campay Sécurisé') }}</span>
                    <span class="text-[11px] leading-relaxed opacity-90">{{ __('Pour vos tests, la demande Mobile Money envoyée sur votre numéro prélève uniquement') }} <strong class="font-black">10 FCFA</strong>{{ __(', et votre compte SafeMarket sera crédité de la totalité du montant choisi.') }}</span>
                </div>
            </div>

            <form wire:submit.prevent="deposit" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1.5">{{ __('Opérateur Mobile Money') }}</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center gap-2 p-3.5 border rounded-2xl cursor-pointer transition" :class="$wire.depositMethod === 'momo' ? 'border-amber-500 bg-amber-50/80 dark:bg-amber-950/30' : 'border-gray-200 dark:border-gray-800'">
                            <input type="radio" wire:model.live="depositMethod" value="momo" class="text-amber-500 focus:ring-amber-500">
                            <span class="text-xs font-black text-amber-800 dark:text-amber-300">MTN MoMo</span>
                        </label>
                        <label class="flex items-center gap-2 p-3.5 border rounded-2xl cursor-pointer transition" :class="$wire.depositMethod === 'om' ? 'border-orange-500 bg-orange-50/80 dark:bg-orange-950/30' : 'border-gray-200 dark:border-gray-800'">
                            <input type="radio" wire:model.live="depositMethod" value="om" class="text-orange-500 focus:ring-orange-500">
                            <span class="text-xs font-black text-orange-800 dark:text-orange-300">Orange Money</span>
                        </label>
                    </div>
                    <x-input-error :messages="$errors->get('depositMethod')" class="mt-1" />
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1.5">{{ __('Montant à Créditer sur le Wallet (FCFA)') }}</label>
                    <input type="number" step="500" wire:model="depositAmount" required placeholder="{{ __('Ex: 10000') }}" class="w-full text-sm font-bold border-gray-200 dark:border-gray-800 dark:bg-gray-950 text-gray-900 dark:text-white rounded-2xl p-3 focus:ring-emerald-500 focus:border-emerald-500">
                    <x-input-error :messages="$errors->get('depositAmount')" class="mt-1" />
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1.5">{{ __('Numéro de Téléphone (ex: 699000000)') }}</label>
                    <input type="text" wire:model="depositPhone" placeholder="699000000" required class="w-full text-sm font-bold border-gray-200 dark:border-gray-800 dark:bg-gray-950 text-gray-900 dark:text-white rounded-2xl p-3 focus:ring-emerald-500 focus:border-emerald-500">
                    <x-input-error :messages="$errors->get('depositPhone')" class="mt-1" />
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="button" @click="showDeposit = false" class="flex-1 py-3 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold text-xs uppercase tracking-wider rounded-2xl transition">{{ __('Annuler') }}</button>
                    <button type="submit" wire:loading.attr="disabled" class="flex-1 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs uppercase tracking-wider rounded-2xl shadow-lg shadow-emerald-600/20 transition flex items-center justify-center gap-2">
                        <span wire:loading.remove>{{ __('Lancer la Recharge') }}</span>
                        <span wire:loading class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            {{ __('Traitement Campay...') }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Withdraw Modal -->
    <div x-show="showWithdraw" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="fixed inset-0 bg-gray-950/60 backdrop-blur-sm" @click="showWithdraw = false"></div>
        <div class="relative bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 p-6 sm:p-8 max-w-md w-full z-10 shadow-2xl">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span>{{ __('Retirer vers Mobile Money') }}</span>
            </h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-6">{{ __('Envoyer vos gains vers votre compte Mobile Money MTN ou Orange Money.') }}</p>

            <form wire:submit.prevent="withdraw" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">{{ __('Mode de Réception') }}</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center gap-2 p-3 border rounded-xl cursor-pointer transition" :class="$wire.withdrawMethod === 'momo' ? 'border-amber-500 bg-amber-50 dark:bg-amber-950/20' : 'border-gray-200 dark:border-gray-800'">
                            <input type="radio" wire:model.live="withdrawMethod" value="momo" class="text-amber-500">
                            <span class="text-xs font-bold text-amber-700 dark:text-amber-400">MTN MoMo</span>
                        </label>
                        <label class="flex items-center gap-2 p-3 border rounded-xl cursor-pointer transition" :class="$wire.withdrawMethod === 'om' ? 'border-orange-500 bg-orange-50 dark:bg-orange-950/20' : 'border-gray-200 dark:border-gray-800'">
                            <input type="radio" wire:model.live="withdrawMethod" value="om" class="text-orange-500">
                            <span class="text-xs font-bold text-orange-700 dark:text-orange-400">Orange Money</span>
                        </label>
                    </div>
                    <x-input-error :messages="$errors->get('withdrawMethod')" class="mt-1" />
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">{{ __('Montant à Retirer (FCFA)') }}</label>
                    <input type="number" step="500" wire:model="withdrawAmount" required class="w-full text-sm border-gray-200 dark:border-gray-800 dark:bg-gray-950 rounded-xl p-3 focus:ring-red-500 focus:border-red-500">
                    <x-input-error :messages="$errors->get('withdrawAmount')" class="mt-1" />
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">{{ __('Numéro Bénéficiaire Mobile Money') }}</label>
                    <input type="text" wire:model="withdrawPhone" placeholder="{{ __('Ex: 677000000') }}" required class="w-full text-sm border-gray-200 dark:border-gray-800 dark:bg-gray-950 rounded-xl p-3 focus:ring-red-500 focus:border-red-500">
                    <x-input-error :messages="$errors->get('withdrawPhone')" class="mt-1" />
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="button" @click="showWithdraw = false" class="flex-1 py-3 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-bold text-sm rounded-xl">{{ __('Annuler') }}</button>
                    <button type="submit" class="flex-1 py-3 bg-red-600 hover:bg-red-700 text-white font-bold text-sm rounded-xl shadow-md">{{ __('Valider le Retrait') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Recent Transactions Table -->
    <div class="bg-white dark:bg-gray-900 border border-gray-200/50 dark:border-gray-800/80 rounded-3xl p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ __('Historique des Transactions Wallet') }}</span>
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800 text-[10px] uppercase font-bold text-gray-400">
                        <th class="py-3 px-4">{{ __('Référence') }}</th>
                        <th class="py-3 px-4">{{ __('Type') }}</th>
                        <th class="py-3 px-4">{{ __('Montant') }}</th>
                        <th class="py-3 px-4">{{ __('Méthode') }}</th>
                        <th class="py-3 px-4">{{ __('Statut') }}</th>
                        <th class="py-3 px-4">{{ __('Date') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800/60 text-xs">
                    @forelse($transactions as $tx)
                        <tr>
                            <td class="py-3.5 px-4 font-mono font-bold text-gray-700 dark:text-gray-300">{{ $tx->reference }}</td>
                            <td class="py-3.5 px-4 font-semibold capitalize">
                                @if($tx->type === 'deposit')
                                    <span class="text-emerald-600 dark:text-emerald-400">{{ __('Dépôt') }}</span>
                                @elseif($tx->type === 'withdrawal')
                                    <span class="text-rose-600 dark:text-rose-400">{{ __('Retrait') }}</span>
                                @else
                                    <span>{{ $tx->type }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 font-bold {{ $tx->type === 'deposit' ? 'text-emerald-600' : 'text-stone-900 dark:text-white' }}">
                                {{ $tx->type === 'deposit' ? '+' : '-' }} {{ number_format($tx->amount, 2) }} FCFA
                            </td>
                            <td class="py-3.5 px-4 uppercase font-bold text-stone-500">{{ $tx->payment_method }}</td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase inline-flex items-center gap-1
                                        @if($tx->status === 'successful') bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400
                                        @elseif($tx->status === 'pending') bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400 animate-pulse
                                        @else bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-400 @endif">
                                        @if($tx->status === 'pending')
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span>
                                        @endif
                                        {{ $tx->status }}
                                    </span>

                                    @if($tx->status === 'pending')
                                        <button type="button" wire:click="checkStatus({{ $tx->id }})" wire:loading.attr="disabled" class="px-2 py-0.5 text-[9px] font-extrabold uppercase bg-amber-500 hover:bg-amber-600 text-white rounded-lg transition shadow-sm flex items-center gap-1" title="{{ __('Vérifier si le paiement a été validé sur le téléphone') }}">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                            <span wire:loading.remove wire:target="checkStatus({{ $tx->id }})">{{ __('Vérifier') }}</span>
                                            <span wire:loading wire:target="checkStatus({{ $tx->id }})">...</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-stone-400">{{ $tx->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-stone-400 italic">{{ __('Aucune transaction récente enregistrée.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
