<div class="w-full">
    @if($cartItems->isEmpty())
        <div class="bg-white dark:bg-gray-900 border border-gray-200/50 dark:border-gray-800/80 rounded-3xl p-16 text-center shadow-sm">
            <div class="w-20 h-20 bg-red-50 dark:bg-red-950/20 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ __('Votre panier est vide.') }}</h3>
            <p class="text-gray-550 dark:text-gray-400 text-sm max-w-sm mx-auto mb-6">{{ __('Ajoutez des produits depuis le marketplace pour les voir s\'afficher ici et négociez leurs tarifs.') }}</p>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-750 text-white font-bold px-6 py-3 rounded-2xl shadow-md transition text-sm">
                <span>{{ __('Parcourir le Marketplace') }}</span>
            </a>
        </div>
    @else
        <div class="flex flex-col lg:flex-row gap-8 items-start">
            <!-- Cart Items List -->
            <div class="w-full lg:w-2/3 space-y-4">
                <div class="bg-white dark:bg-gray-900 border border-gray-200/50 dark:border-gray-800/80 rounded-3xl p-6 shadow-sm">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <span>{{ __('Mon Panier') }}</span>
                            <span class="px-2.5 py-0.5 text-xs bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 font-bold rounded-full">{{ $cartItems->count() }}</span>
                        </h3>
                        <button wire:click="clearCart" wire:confirm="{{ __('Voulez-vous vraiment vider tout le panier ?') }}" class="text-xs text-rose-500 hover:text-rose-600 font-semibold transition">
                            {{ __('Vider le panier') }}
                        </button>
                    </div>

                    <div class="divide-y divide-gray-100 dark:divide-gray-800/80">
                        @foreach($cartItems as $item)
                            @php 
                                $negotiation = $negotiations->get($item->product_id);
                                $price = $negotiation ? $negotiation->proposed_price : $item->product->effective_price;
                            @endphp
                            <div class="flex flex-col sm:flex-row sm:items-center gap-5 py-5 first:pt-0 last:pb-0">
                                <!-- Image -->
                                <div class="w-20 h-20 md:w-24 md:h-24 bg-gray-50 dark:bg-gray-950 rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-800 shrink-0">
                                    <img src="{{ $item->product->getImageUrl() }}" alt="{{ $item->product->title }}" class="w-full h-full object-cover">
                                </div>

                                <!-- Text Details -->
                                <div class="flex-grow min-w-0">
                                    <h4 class="font-bold text-gray-900 dark:text-white text-base truncate">{{ $item->product->title }}</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ __('Vendu par') }} : <strong class="font-semibold text-gray-700 dark:text-gray-300">{{ $item->product->vendor->name }}</strong></p>
                                    @if($negotiation)
                                        <div class="mt-2">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 text-[9px] font-extrabold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 rounded-md border border-emerald-100 dark:border-emerald-900/50 uppercase tracking-wide">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                {{ __('Prix Négocié Appliqué') }} ({{ number_format($negotiation->proposed_price, 2) }} FCFA)
                                            </span>
                                        </div>
                                    @elseif($item->product->is_on_sale && $item->product->discount_price > 0)
                                        <div class="mt-2">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 text-[9px] font-extrabold bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-400 rounded-md border border-rose-100 dark:border-rose-900/50 uppercase tracking-wide">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                {{ __('En Promo') }} ({{ __('Économie') }} : {{ number_format($item->product->price - $item->product->discount_price, 0) }} FCFA)
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Price and Quantity Controls -->
                                <div class="flex sm:flex-col justify-between items-end shrink-0 sm:text-right gap-2">
                                    <div>
                                        <p class="font-extrabold text-red-600 dark:text-red-400 text-lg">{{ number_format($price * $item->quantity, 2) }} FCFA</p>
                                        <p class="text-[11px] text-gray-400 mt-0.5">{{ number_format($price, 2) }} FCFA / {{ __('unité') }}</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden bg-gray-50 dark:bg-gray-800">
                                            <button wire:click="decrementQuantity({{ $item->id }})" class="px-2.5 py-1 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 text-xs font-bold transition">-</button>
                                            <span class="px-3 py-1 text-xs font-bold text-gray-800 dark:text-gray-200 min-w-[24px] text-center">{{ $item->quantity }}</span>
                                            <button wire:click="incrementQuantity({{ $item->id }})" class="px-2.5 py-1 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 text-xs font-bold transition">+</button>
                                        </div>
                                        <button wire:click="removeItem({{ $item->id }})" class="text-rose-500 hover:text-rose-700 p-1 rounded-lg transition group" title="{{ __('Supprimer') }}">
                                            <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Summary Right Sidebar -->
            <div class="w-full lg:w-1/3">
                <div class="bg-white dark:bg-gray-900 border border-gray-200/50 dark:border-gray-800/80 rounded-3xl p-6 shadow-sm sticky top-24">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 border-b border-gray-100 dark:border-gray-850 pb-3">{{ __('Résumé de la commande') }}</h3>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500 dark:text-gray-400">{{ __('Total articles') }}</span>
                            <span class="font-bold text-gray-900 dark:text-white">{{ $cartItems->sum('quantity') }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center border-t border-gray-100 dark:border-gray-850 pt-4">
                            <span class="text-gray-550 dark:text-gray-400 font-medium uppercase text-xs tracking-wider">{{ __('ui.total') }}</span>
                            <span class="text-3xl font-black text-red-650 dark:text-red-400 tracking-tight">{{ number_format($total, 2) }} FCFA</span>
                        </div>

                        <!-- Wallet info box -->
                        <div class="p-4 bg-gray-50 dark:bg-gray-800/40 rounded-2xl border border-gray-100 dark:border-gray-800/50 space-y-2">
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-gray-500 dark:text-gray-400 font-semibold">{{ __('ui.wallet_balance') }}</span>
                                <span class="font-bold text-gray-950 dark:text-white">{{ number_format(auth()->user()->balance, 2) }} FCFA</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-6">
                        @if($paymentErrorMessage)
                            <div class="mb-4 p-3.5 bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-900/50 rounded-2xl text-rose-800 dark:text-rose-400 font-bold text-xs flex items-center gap-2">
                                <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>{{ $paymentErrorMessage }}</span>
                            </div>
                        @endif

                        <form wire:submit.prevent="submitCheckout" class="space-y-4">
                            <div class="space-y-1.5 text-left">
                                <label class="text-[10px] font-black uppercase text-gray-500 dark:text-gray-450">{{ __('Téléphone Mobile Money') }}</label>
                                <input type="tel" wire:model="phone" required placeholder="Ex: 699000000" class="w-full text-xs font-bold border border-gray-200 dark:border-gray-800 rounded-xl px-4 py-3 bg-gray-50 dark:bg-gray-950 focus:outline-none focus:border-red-500 text-gray-900 dark:text-white">
                                <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                            </div>

                            <div class="space-y-1.5 text-left">
                                <label class="text-[10px] font-black uppercase text-gray-500 dark:text-gray-450">{{ __('Localisation / Adresse de livraison') }}</label>
                                <textarea wire:model="location" required rows="2" placeholder="{{ __('Ex: Akwa, Douala (Face Direction Générale SNH)') }}" class="w-full text-xs font-medium border border-gray-200 dark:border-gray-800 rounded-xl px-4 py-3 bg-gray-50 dark:bg-gray-950 focus:outline-none focus:border-red-500 text-gray-900 dark:text-white"></textarea>
                                <x-input-error :messages="$errors->get('location')" class="mt-1" />
                            </div>

                            <div class="space-y-2 text-left pt-2">
                                <label class="text-[10px] font-black uppercase text-gray-500 dark:text-gray-450">{{ __('Mode de Paiement ESCROW') }}</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <label class="p-3 border rounded-xl flex flex-col cursor-pointer transition" :class="$wire.paymentMethod === 'wallet' ? 'border-red-500 bg-red-50/20' : 'border-gray-200 dark:border-gray-800'">
                                        <div class="flex items-center gap-2">
                                            <input type="radio" wire:model.live="paymentMethod" value="wallet" class="text-red-600">
                                            <span class="text-xs font-bold text-gray-800 dark:text-gray-200">Wallet</span>
                                        </div>
                                        <span class="text-[10px] text-gray-500 mt-1">{{ number_format(auth()->user()->balance, 2) }} FCFA {{ __('dispo') }}</span>
                                    </label>
                                    <label class="p-3 border rounded-xl flex flex-col cursor-pointer transition" :class="$wire.paymentMethod === 'campay' ? 'border-red-500 bg-red-50/20' : 'border-gray-200 dark:border-gray-800'">
                                        <div class="flex items-center gap-2">
                                            <input type="radio" wire:model.live="paymentMethod" value="campay" class="text-red-600">
                                            <span class="text-xs font-bold text-gray-800 dark:text-gray-200">CamPay (MoMo/OM)</span>
                                        </div>
                                        <span class="text-[10px] text-emerald-600 font-bold mt-1">{{ __('Paiement Direct') }}</span>
                                    </label>
                                </div>
                            </div>

                            @if($paymentMethod === 'campay')
                                <div class="space-y-1.5 text-left pt-1">
                                    <label class="text-[10px] font-black uppercase text-gray-500 dark:text-gray-450">{{ __('Opérateur Mobile') }}</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <label class="flex items-center gap-2 p-2.5 border rounded-xl cursor-pointer text-xs font-bold" :class="$wire.campayMethod === 'momo' ? 'border-amber-500 bg-amber-50 dark:bg-amber-950/30 text-amber-700 dark:text-amber-300' : 'border-gray-200 dark:border-gray-800'">
                                            <input type="radio" wire:model.live="campayMethod" value="momo" class="text-amber-500">
                                            <span>MTN MoMo</span>
                                        </label>
                                        <label class="flex items-center gap-2 p-2.5 border rounded-xl cursor-pointer text-xs font-bold" :class="$wire.campayMethod === 'om' ? 'border-orange-500 bg-orange-50 dark:bg-orange-950/30 text-orange-700 dark:text-orange-300' : 'border-gray-200 dark:border-gray-800'">
                                            <input type="radio" wire:model.live="campayMethod" value="om" class="text-orange-500">
                                            <span>Orange Money</span>
                                        </label>
                                    </div>
                                </div>
                            @endif

                            <button type="submit" wire:loading.attr="disabled" class="w-full bg-red-600 hover:bg-red-750 dark:bg-red-550 dark:hover:bg-red-650 text-white font-bold py-4 rounded-2xl shadow-lg shadow-red-100 dark:shadow-none transition duration-200 flex items-center justify-center gap-2">
                                <span wire:loading.remove wire:target="submitCheckout" class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                    {{ __('ui.place_order_escrow') }}
                                </span>
                                <span wire:loading wire:target="submitCheckout" class="flex items-center gap-2 text-xs">
                                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    {{ __('Envoi de la demande sur votre téléphone...') }}
                                </span>
                            </button>
                        </form>
                        
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 text-center mt-3.5 leading-normal">
                            🔒 {{ __('Vos fonds seront bloqués de manière sécurisée en ESCROW via Campay jusqu\'à ce que vous validiez la réception conforme de votre commande.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Awaiting Mobile Money Phone Confirmation Modal -->
    @if($isWaitingPayment)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" wire:poll.3s="checkPaymentStatus">
            <div class="fixed inset-0 bg-gray-950/80 backdrop-blur-md"></div>
            <div class="relative bg-white dark:bg-gray-900 rounded-[2.5rem] border border-gray-100 dark:border-gray-800 p-8 max-w-md w-full z-10 shadow-2xl text-center">
                <div class="w-16 h-16 rounded-3xl bg-amber-500/10 text-amber-500 flex items-center justify-center mx-auto mb-4 animate-bounce">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                </div>

                <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest bg-amber-500/10 text-amber-600 dark:text-amber-400 rounded-full border border-amber-500/20 inline-block mb-2">
                    {{ __('Validation Mobile Money Requise') }}
                </span>

                <h3 class="text-xl font-extrabold text-gray-900 dark:text-white mb-2">
                    {{ __('Confirmez sur votre téléphone') }}
                </h3>

                <p class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed mb-6">
                    {{ $paymentStatusMessage }}
                </p>

                <div class="p-4 bg-gray-50 dark:bg-gray-950 rounded-2xl border border-gray-100 dark:border-gray-800 mb-6 flex items-center justify-between text-xs">
                    <span class="text-gray-400 font-bold uppercase">{{ __('Montant Total') }}</span>
                    <span class="text-lg font-black text-red-600 dark:text-red-400">{{ number_format($total, 2) }} FCFA</span>
                </div>

                <div class="flex items-center justify-center gap-2 mb-6 text-xs text-amber-600 dark:text-amber-400 font-semibold">
                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span>{{ __('En attente de votre validation par code secret...') }}</span>
                </div>

                <div class="flex gap-3">
                    <button wire:click="cancelWaitingPayment" class="flex-1 py-3 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold text-xs uppercase rounded-2xl transition">
                        {{ __('Annuler') }}
                    </button>
                    <button wire:click="checkPaymentStatus" class="flex-1 py-3 bg-amber-600 hover:bg-amber-700 text-white font-extrabold text-xs uppercase rounded-2xl shadow-lg shadow-amber-600/20 transition flex items-center justify-center gap-1.5">
                        <span>{{ __('J\'ai validé') }}</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
