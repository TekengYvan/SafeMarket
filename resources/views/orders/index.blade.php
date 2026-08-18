<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mes Commandes et Ventes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Purchases (Buying) -->
            <div class="mb-12">
                <h3 class="text-2xl font-bold mb-6">{{ __('Mes Achats') }}</h3>
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Produit') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Vendeur') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Montant') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Statut') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($purchases as $order)
                                <tr>
                                    <td class="px-6 py-4">{{ $order->product->title }}</td>
                                    <td class="px-6 py-4">{{ $order->product->vendor->name }}</td>
                                    <td class="px-6 py-4 font-bold">{{ number_format($order->amount, 2) }} FCFA</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded-full text-xs font-bold uppercase
                                            @if($order->status == 'funds_held') bg-blue-100 text-blue-800 
                                            @elseif($order->status == 'shipped') bg-yellow-100 text-yellow-800
                                            @elseif($order->status == 'delivered') bg-emerald-100 text-emerald-800
                                            @elseif($order->status == 'completed') bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 flex items-center gap-3">
                                        <a href="{{ route('orders.show', $order) }}" class="text-red-650 hover:underline font-semibold">{{ __('Gérer') }}</a>
                                        @if($order->status == 'funds_held' || $order->status == 'shipped' || $order->status == 'delivered')
                                            <livewire:marketplace.dispute-button :order="$order" :wire:key="'dispute-'.$order->id" />
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">{{ __('Aucun achat effectué.') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Sales (Selling) -->
            <div>
                <h3 class="text-2xl font-bold mb-6">{{ __('Mes Ventes') }}</h3>
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Produit') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Acheteur') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Montant') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Statut') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($sales as $order)
                                <tr>
                                    <td class="px-6 py-4">{{ $order->product->title }}</td>
                                    <td class="px-6 py-4">{{ $order->buyer->name }}</td>
                                    <td class="px-6 py-4 font-bold">{{ number_format($order->amount, 2) }} FCFA</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded-full text-xs font-bold uppercase
                                            @if($order->status == 'funds_held') bg-blue-100 text-blue-800 
                                            @elseif($order->status == 'shipped') bg-yellow-100 text-yellow-800
                                            @elseif($order->status == 'delivered') bg-emerald-100 text-emerald-800
                                            @elseif($order->status == 'completed') bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 flex items-center gap-3">
                                        <a href="{{ route('orders.show', $order) }}" class="text-red-650 hover:underline font-semibold">{{ __('Gérer') }}</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">{{ __('Aucune vente enregistrée.') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
