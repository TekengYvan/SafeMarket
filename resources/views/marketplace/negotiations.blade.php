<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mes Offres de Négociation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Produit') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Vendeur') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Prix Original') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Ma Proposition') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Statut') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($negotiations as $neg)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    <a href="{{ route('products.show', $neg->product) }}" class="text-indigo-600 hover:underline">{{ $neg->product->title }}</a>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $neg->seller->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ number_format($neg->original_price, 2) }} FCFA</td>
                                <td class="px-6 py-4 font-bold text-indigo-600">{{ number_format($neg->proposed_price, 2) }} FCFA</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-bold uppercase
                                        @if($neg->status == 'pending') bg-yellow-100 text-yellow-800 
                                        @elseif($neg->status == 'accepted') bg-green-100 text-green-800
                                        @elseif($neg->status == 'rejected') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ $neg->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium">
                                    <a href="{{ route('negotiations.show', $neg) }}" class="text-indigo-600 hover:underline">{{ __('Discuter') }} 💬</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">{{ __('Vous n\'avez envoyé aucune proposition.') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $negotiations->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
