<div class="bg-white dark:bg-gray-900 p-6 rounded-3xl border border-gray-200/50 dark:border-gray-800/80 shadow-sm">
    <h3 class="text-xl font-black text-gray-900 dark:text-white mb-6">{{ __('Commandes Reçues') }}</h3>

    @if($orders->isEmpty())
        <div class="text-center py-12 text-gray-400 dark:text-gray-500">
            <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            <p class="text-sm font-medium">{{ __('Aucune commande reçue pour le moment.') }}</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-gray-200/50 dark:border-gray-800/80 text-xs font-bold text-gray-400 uppercase tracking-wider">
                        <th class="pb-3">{{ __('Commande ID') }}</th>
                        <th class="pb-3">{{ __('Produit') }}</th>
                        <th class="pb-3">{{ __('Acheteur') }}</th>
                        <th class="pb-3">{{ __('Montant') }}</th>
                        <th class="pb-3">{{ __('Statut') }}</th>
                        <th class="pb-3">{{ __('Actions & Suivi') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800/60 text-sm">
                    @foreach($orders as $order)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-850/20 transition">
                            <td class="py-4 font-mono text-xs text-gray-500">#{{ $order->id }}</td>
                            <td class="py-4 font-semibold text-gray-900 dark:text-white">{{ $order->product->title }}</td>
                            <td class="py-4 text-gray-650 dark:text-gray-400">
                                <div>{{ $order->buyer->name }}</div>
                                @if($order->phone || $order->location)
                                    <div class="text-[10px] text-stone-400 dark:text-zinc-550 mt-1 max-w-[200px] leading-tight">
                                        @if($order->phone) 📱 {{ $order->phone }} <br/> @endif
                                        @if($order->location) 📍 {{ $order->location }} @endif
                                    </div>
                                @endif
                            </td>
                            <td class="py-4 font-bold text-gray-900 dark:text-white">{{ number_format($order->amount, 2) }} €</td>
                            <td class="py-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wide
                                    @if($order->status == 'funds_held') bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 border border-blue-100 dark:border-blue-900/30
                                    @elseif($order->status == 'shipped') bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 border border-amber-100 dark:border-amber-900/30
                                    @elseif($order->status == 'delivered') bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-450 border border-emerald-100 dark:border-emerald-900/30
                                    @elseif($order->status == 'completed') bg-green-50 dark:bg-green-950/40 text-green-700 dark:text-green-400 border border-green-100 dark:border-green-900/30
                                    @else bg-gray-50 dark:bg-gray-950 text-gray-700 dark:text-gray-400 border border-gray-100 dark:border-gray-800 @endif">
                                    @if($order->status == 'funds_held') {{ __('En Escrow') }}
                                    @elseif($order->status == 'shipped') {{ __('Expédié') }}
                                    @elseif($order->status == 'delivered') {{ __('Livré') }}
                                    @elseif($order->status == 'completed') {{ __('Terminé') }}
                                    @else {{ $order->status }} @endif
                                </span>
                            </td>
                            <td class="py-4">
                                @if(session()->has("status-{$order->id}"))
                                    <span class="text-xs text-emerald-600 font-bold block">{{ session("status-{$order->id}") }}</span>
                                @endif

                                @if($order->status == 'funds_held')
                                    <form wire:submit.prevent="shipOrder({{ $order->id }})" class="flex gap-2">
                                        <input type="text" wire:model="trackingNumber.{{ $order->id }}" placeholder="{{ __('N° de suivi...') }}" class="border-gray-200 dark:border-gray-800 dark:bg-gray-950 focus:border-red-500 focus:ring-red-500 rounded-xl px-3 py-1 text-xs w-48 transition-all" required>
                                        <button type="submit" class="bg-red-600 hover:bg-red-750 text-white font-semibold px-3 py-1 rounded-xl text-xs transition">
                                            {{ __('Expédier') }}
                                        </button>
                                    </form>
                                    <x-input-error :messages="$errors->get('trackingNumber.' . $order->id)" class="mt-1" />
                                @elseif($order->status == 'shipped')
                                    <div class="space-y-2">
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            <span>{{ __('Suivi :') }} </span><strong class="font-mono bg-gray-100 dark:bg-gray-950 px-1.5 py-0.5 rounded">{{ $order->tracking_number }}</strong>
                                        </div>
                                        <button wire:click="deliverOrder({{ $order->id }})" class="bg-red-650 hover:bg-red-750 text-white font-semibold px-3 py-1 rounded-xl text-xs transition">
                                            {{ __('Marquer Livré 📦') }}
                                        </button>
                                    </div>
                                @elseif($order->status == 'delivered')
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        <span>{{ __('Suivi :') }} </span><strong class="font-mono bg-gray-100 dark:bg-gray-950 px-1.5 py-0.5 rounded">{{ $order->tracking_number }}</strong>
                                        <p class="text-[10px] text-gray-400 mt-1">{{ __('Marqué livré. En attente de confirmation client...') }}</p>
                                    </div>
                                @elseif($order->status == 'completed')
                                    <div class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        {{ __('Fonds libérés') }}
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
