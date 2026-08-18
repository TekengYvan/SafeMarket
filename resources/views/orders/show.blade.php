<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            <span>{{ __('Détails de la Commande') }} #{{ $order->id }}</span>
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50/50 dark:bg-gray-950/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if (session()->has('status'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-900/50 rounded-2xl text-emerald-800 dark:text-emerald-400 font-bold text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="p-4 bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-900/50 rounded-2xl text-rose-800 dark:text-rose-400 font-bold text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-sm rounded-3xl border border-gray-200/50 dark:border-gray-800/80 p-6 md:p-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                    <!-- Left: Order Summary Card -->
                    <div class="space-y-6">
                        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-4">
                            <h3 class="text-xl font-extrabold text-gray-900 dark:text-white">{{ __('Informations Générales') }}</h3>
                            <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider
                                @if($order->status == 'funds_held') bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-400
                                @elseif($order->status == 'shipped') bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400
                                @elseif($order->status == 'delivered') bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400
                                @elseif($order->status == 'completed') bg-green-50 text-green-700 dark:bg-green-950/40 dark:text-green-400
                                @else bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200 @endif">
                                {{ $order->status }}
                            </span>
                        </div>

                        <div class="space-y-4 text-sm">
                            <div class="flex justify-between items-center py-2 border-b border-gray-50 dark:border-gray-850">
                                <span class="text-gray-500 dark:text-gray-400">{{ __('Produit') }}:</span>
                                <span class="font-bold text-gray-900 dark:text-white text-base">{{ $order->product->title }}</span>
                            </div>

                            <div class="flex justify-between items-center py-2 border-b border-gray-50 dark:border-gray-850">
                                <span class="text-gray-500 dark:text-gray-400">{{ __('Vendeur') }}:</span>
                                <span class="font-bold text-gray-900 dark:text-white">{{ $order->product->vendor->name }}</span>
                            </div>

                            <div class="flex justify-between items-center py-2 border-b border-gray-50 dark:border-gray-850">
                                <span class="text-gray-500 dark:text-gray-400">{{ __('Montant Séquestré (ESCROW)') }}:</span>
                                <span class="font-black text-xl text-red-600 dark:text-red-400">{{ number_format($order->amount, 2) }} FCFA</span>
                            </div>

                            @if($order->phone)
                                <div class="flex justify-between items-center py-2 border-b border-gray-50 dark:border-gray-850">
                                    <span class="text-gray-500 dark:text-gray-400">{{ __('Téléphone de livraison') }}:</span>
                                    <span class="font-bold text-gray-900 dark:text-white">{{ $order->phone }}</span>
                                </div>
                            @endif

                            @if($order->location)
                                <div class="flex justify-between items-start py-2 border-b border-gray-50 dark:border-gray-850">
                                    <span class="text-gray-500 dark:text-gray-400 shrink-0">{{ __('Adresse de livraison') }}:</span>
                                    <span class="font-bold text-gray-900 dark:text-white text-right ml-4">{{ $order->location }}</span>
                                </div>
                            @endif

                            @if($order->tracking_number)
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-gray-500 dark:text-gray-400">{{ __('N° de Suivi d\'expédition') }}:</span>
                                    <span class="font-mono bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-3 py-1 rounded-xl text-xs font-bold">{{ $order->tracking_number }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Right: Dynamic Actions & Release Code Security Box -->
                    <div class="bg-gray-50/70 dark:bg-gray-950/50 p-6 sm:p-8 rounded-3xl border border-gray-200/50 dark:border-gray-800/80 flex flex-col justify-between">
                        <div>
                            <h3 class="text-xl font-extrabold text-gray-900 dark:text-white mb-6 border-b border-gray-200 dark:border-gray-800 pb-3">{{ __('Actions & Validation') }}</h3>

                            @if(auth()->id() === $order->product->vendor_id)
                                <!-- VENDOR ACTIONS -->
                                @if($order->status === 'funds_held')
                                    <div class="p-4 bg-blue-50 dark:bg-blue-950/20 border border-blue-200 dark:border-blue-900/50 rounded-2xl mb-6 text-blue-800 dark:text-blue-400 text-xs leading-relaxed">
                                        {{ __('Les fonds de l\'acheteur sont sécurisés en ESCROW. Veuillez expédier le colis et saisir le numéro de suivi ci-dessous.') }}
                                    </div>
                                    <form action="{{ route('orders.ship', $order) }}" method="POST" class="space-y-4">
                                        @csrf
                                        <div>
                                            <x-input-label for="tracking_number" :value="__('Numéro de suivi du colis')" />
                                            <x-text-input id="tracking_number" class="block mt-1 w-full text-sm" type="text" name="tracking_number" required placeholder="Ex: SM-CAM-98421" />
                                        </div>
                                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3.5 rounded-2xl shadow-md transition text-sm flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            {{ __('Confirmer l\'expédition') }}
                                        </button>
                                    </form>
                                @elseif($order->status === 'shipped')
                                    <div class="p-4 bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900/50 rounded-2xl mb-6 text-amber-800 dark:text-amber-400 text-xs leading-relaxed">
                                        {{ __('Le colis est en cours de livraison. Une fois remis en main propre à l\'acheteur, marquez la commande comme livrée.') }}
                                    </div>
                                    <form action="{{ route('orders.deliver', $order) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3.5 rounded-2xl shadow-md transition text-sm flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                            {{ __('Marquer comme Livré au Client') }}
                                        </button>
                                    </form>
                                @elseif($order->status === 'delivered')
                                    <div class="text-center py-8 text-stone-500 dark:text-stone-400 text-xs font-semibold italic">
                                        {{ __('Commande marquée comme livrée ! En attente de la saisie du code de libération par l\'acheteur pour le versement automatique de vos fonds.') }}
                                    </div>
                                @elseif($order->status === 'completed')
                                    <div class="text-center py-8 space-y-2">
                                        <div class="w-16 h-16 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-2">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                        <p class="font-extrabold text-emerald-600 dark:text-emerald-400 text-base">{{ __('Vente Réussie & Payout Effectué !') }}</p>
                                        <p class="text-xs text-stone-400">{{ __('Les fonds (nets de 5% de taxe plateforme) sont crédités sur votre Wallet.') }}</p>
                                    </div>
                                @endif
                            @else
                                <!-- BUYER ACTIONS & RELEASE CODE SYSTEM -->
                                @if($order->status === 'funds_held')
                                    <div class="p-4 bg-blue-50 dark:bg-blue-950/20 border border-blue-200 dark:border-blue-900/50 rounded-2xl text-blue-800 dark:text-blue-400 text-xs leading-relaxed">
                                        {{ __('Votre paiement est consigné en toute sécurité sur le compte ESCROW SafeMarket. Le vendeur a été notifié pour préparer votre livraison.') }}
                                    </div>
                                @elseif($order->status === 'shipped' || $order->status === 'delivered')
                                    <!-- PROMINENT RELEASE CODE BOX FOR BUYER -->
                                    <div class="p-5 bg-gradient-to-br from-zinc-900 to-stone-900 text-white rounded-2xl shadow-lg border border-zinc-800 mb-6 space-y-3" x-data="{ copied: false }">
                                        <div class="flex items-center justify-between">
                                            <span class="text-[10px] font-bold uppercase tracking-widest text-amber-400 flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                                {{ __('Code Unique de Libération') }}
                                            </span>
                                            <span class="text-[9px] bg-amber-500/20 text-amber-300 px-2 py-0.5 rounded font-bold uppercase">{{ __('Secret Acheteur') }}</span>
                                        </div>

                                        <p class="text-xs text-stone-300">
                                            {{ __('Gardez ce code secret ! Ne le remettez au livreur qu\'après avoir réceptionné et vérifié la conformité de votre article.') }}
                                        </p>

                                        <div class="flex items-center justify-between bg-black/50 p-3 rounded-xl border border-zinc-700">
                                            <span class="font-mono text-xl font-black tracking-widest text-white">{{ $order->release_code }}</span>
                                            <button type="button" @click="navigator.clipboard.writeText('{{ $order->release_code }}'); copied = true; setTimeout(() => copied = false, 2000)" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white font-bold text-xs rounded-lg transition flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                                                <span x-text="copied ? 'Copié !' : 'Copier'"></span>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- RELEASE CODE FORM INPUT -->
                                    <form action="{{ route('orders.complete', $order) }}" method="POST" class="space-y-4">
                                        @csrf
                                        <div>
                                            <x-input-label for="release_code" :value="__('Entrez le Code de Libération pour Valider')" />
                                            <x-text-input id="release_code" class="block mt-1 w-full text-sm font-mono tracking-widest" type="text" name="release_code" required placeholder="Ex: {{ $order->release_code }}" />
                                        </div>
                                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 rounded-2xl shadow-md transition text-sm flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ __('Confirmer la Réception & Libérer les Fonds') }}
                                        </button>
                                    </form>
                                @elseif($order->status === 'completed')
                                    <div class="text-center py-8 space-y-2">
                                        <div class="w-16 h-16 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-2">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                        <p class="font-extrabold text-emerald-600 dark:text-emerald-400 text-base">{{ __('Commande Terminée avec Succès !') }}</p>
                                        <p class="text-xs text-stone-400">{{ __('Merci d\'avoir utilisé SafeMarket ESCROW.') }}</p>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                @if($order->status === 'completed')
                    <div class="mt-8 pt-8 border-t border-gray-100 dark:border-gray-800">
                        <livewire:marketplace.review-system :order="$order" />
                    </div>
                @endif

                @if($order->is_disputed)
                    <div class="mt-8 bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-900/50 p-6 rounded-3xl">
                        <h3 class="text-rose-800 dark:text-rose-400 font-bold flex items-center gap-2 mb-2 text-base">
                            <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <span>{{ __('Commande actuellement en Litige') }}</span>
                        </h3>
                        <p class="text-xs text-rose-700 dark:text-rose-400/90 mb-3">{{ __('Un litige a été ouvert pour cette commande. Les fonds sont bloqués jusqu\'à arbitrage final par l\'administration.') }}</p>
                        <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl border border-rose-100 dark:border-rose-900/30 text-xs italic text-gray-700 dark:text-gray-300">
                            "{{ $order->dispute_reason }}"
                        </div>
                    </div>
                @elseif(auth()->id() === $order->buyer_id && $order->status !== 'completed' && $order->status !== 'cancelled')
                    <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-rose-50/30 dark:bg-rose-950/10 p-5 rounded-2xl border border-rose-100 dark:border-rose-900/30">
                        <div>
                            <p class="text-xs font-bold text-rose-700 dark:text-rose-400">{{ __('Un problème avec votre livraison ou l\'article ?') }}</p>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400">{{ __('Ouvrez un litige pour consigner l\'argent en ESCROW et solliciter l\'arbitrage de l\'administrateur.') }}</p>
                        </div>
                        <livewire:marketplace.dispute-button :order="$order" :wire:key="'dispute-'.$order->id" />
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
