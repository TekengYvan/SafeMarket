<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
    <h3 class="text-xl font-bold mb-6 text-red-600">{{ __('Gestion des Litiges') }}</h3>

    @if(session()->has('status'))
        <div class="mb-4 p-3 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-xl text-sm font-bold">
            {{ session('status') }}
        </div>
    @endif

    <div class="space-y-4">
        @forelse($disputes as $dispute)
            <div class="border dark:border-gray-700 p-4 rounded-xl flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-gray-50/50 dark:bg-gray-900/50">
                <div class="flex-grow">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="font-bold text-lg text-rose-600">{{ __('Litige') }} #{{ $dispute->id }}</span>
                        <span class="text-xs bg-white dark:bg-gray-800 px-2 py-0.5 rounded font-mono border dark:border-gray-700">{{ $dispute->product?->title ?? __('Produit') . ' #' . $dispute->product_id }}</span>
                    </div>
                    <p class="text-xs text-gray-500 mb-2">
                        {{ __('Acheteur') }}: <strong class="text-gray-700 dark:text-gray-300">{{ $dispute->buyer?->name ?? __('Acheteur inconnu') }}</strong> 
                        • {{ __('Vendeur') }}: <strong class="text-gray-700 dark:text-gray-300">{{ $dispute->product?->vendor?->name ?? __('Vendeur inconnu') }}</strong>
                    </p>
                    <div class="bg-red-50 dark:bg-red-900/10 p-3 rounded-xl text-xs italic border-l-4 border-red-500 text-gray-800 dark:text-gray-200">
                        "{{ $dispute->dispute_reason ?? __('Motif non précisé') }}"
                    </div>
                </div>
                <div class="flex flex-col gap-2 min-w-[200px]">
                    <div class="text-right font-black text-xl text-gray-900 dark:text-white mb-1">{{ number_format($dispute->amount, 2) }} FCFA</div>
                    <button wire:click="resolve({{ $dispute->id }}, 'buyer')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl font-bold text-xs shadow-sm transition">{{ __('Rembourser l\'acheteur') }}</button>
                    <button wire:click="resolve({{ $dispute->id }}, 'vendor')" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl font-bold text-xs shadow-sm transition">{{ __('Payer le vendeur') }}</button>
                </div>
            </div>
        @empty
            <p class="text-center py-8 text-gray-400 italic text-sm">{{ __('Aucun litige actif en attente d\'arbitrage.') }}</p>
        @endforelse
    </div>
</div>
