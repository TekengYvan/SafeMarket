<div x-data="{ showModal: false }">
    @if(!$order->is_disputed && $order->status !== 'completed')
        <button @click="showModal = true" class="inline-flex items-center gap-1.5 text-xs text-rose-600 dark:text-rose-400 font-bold hover:underline">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <span>{{ __('Signaler un problème / Litige') }}</span>
        </button>
    @elseif($order->is_disputed)
        <span class="inline-flex items-center gap-1 text-[10px] bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-400 px-2.5 py-0.5 rounded-full font-bold uppercase border border-rose-200 dark:border-rose-900/50">
            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-ping"></span>
            {{ __('En Litige') }}
        </span>
    @endif

    <!-- Centered Dispute Popup Modal -->
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 min-h-screen" x-cloak>
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-950/60 backdrop-blur-sm" @click="showModal = false"></div>

        <!-- Modal Card -->
        <div class="relative bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 p-6 sm:p-8 max-w-lg w-full z-10 shadow-2xl">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Ouvrir un Litige sur la Commande') }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Produit') }} : {{ $order->product->title }}</p>
                </div>
            </div>

            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed mb-4">
                {{ __('Veuillez expliciter clairement le motif du litige. L\'ouverture d\'un litige bloque la libération automatique des fonds au vendeur jusqu\'à étude par l\'administration SafeMarket.') }}
            </p>

            <form wire:submit.prevent="openDispute" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">{{ __('Motif détaillé du litige') }}</label>
                    <textarea wire:model="reason" rows="4" required class="w-full border-gray-200 dark:border-gray-800 dark:bg-gray-950 rounded-xl p-3 text-sm focus:ring-rose-500 focus:border-rose-500 text-gray-900 dark:text-white" placeholder="{{ __('Expliquez pourquoi le produit n\'est pas conforme, endommagé ou non reçu...') }}"></textarea>
                    <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="showModal = false" class="px-5 py-2.5 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-bold text-xs rounded-xl">
                        {{ __('Annuler') }}
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-1.5" wire:loading.attr="disabled">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        <span wire:loading.remove>{{ __('Bloquer les Fonds & Déclarer le Litige') }}</span>
                        <span wire:loading>{{ __('Envoi en cours...') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
