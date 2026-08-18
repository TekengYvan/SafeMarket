<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Demandes de Négociation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Produit') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Acheteur') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Prix Original') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Proposition') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Statut') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($negotiations as $neg)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $neg->product->title }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $neg->buyer->name }}</td>
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
                                <td class="px-6 py-4 text-sm font-medium flex space-x-2">
                                    <a href="{{ route('negotiations.show', $neg) }}" class="text-indigo-600 hover:underline mr-2">{{ __('Discuter 💬') }}</a>
                                    @if($neg->status == 'pending')
                                        <form action="{{ route('negotiations.update', $neg) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="accepted">
                                            <button type="submit" class="text-green-600 hover:text-green-900">{{ __('Accepter') }}</button>
                                        </form>
                                        <form action="{{ route('negotiations.update', $neg) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="text-red-600 hover:text-red-900">{{ __('Refuser') }}</button>
                                        </form>
                                    @else
                                        <span class="text-gray-400 italic">{{ __('Terminé') }}</span>
                                    @endif
                                </td>
                            </tr>
                            @if($neg->message)
                                <tr class="bg-gray-50">
                                    <td colspan="6" class="px-6 py-2 text-xs text-gray-600">
                                        <strong>{{ __('Message:') }}</strong> {{ $neg->message }}
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">{{ __('Aucune demande de négociation.') }}</td></tr>
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
