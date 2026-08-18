<div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
    <h3 class="text-xl font-bold mb-6">{{ __('Vérifications KYC en attente') }}</h3>

    @if (session()->has('status'))
        <div class="mb-4 text-sm font-medium text-green-600">
            {{ session('status') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b dark:border-gray-700">
                    <th class="pb-3">{{ __('Utilisateur') }}</th>
                    <th class="pb-3">{{ __('Document') }}</th>
                    <th class="pb-3">{{ __('Date d\'envoi') }}</th>
                    <th class="pb-3">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingUsers as $user)
                    <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-900/50 transition">
                        <td class="py-3">
                            <div class="font-bold">{{ $user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $user->email }}</div>
                        </td>
                        <td class="py-3">
                            @if($user->getKycDocumentUrl())
                                <div class="flex items-center gap-3">
                                    <img src="{{ $user->getKycDocumentUrl() }}" class="w-10 h-10 rounded-lg object-cover border border-gray-200 dark:border-gray-700 shadow-sm">
                                    <a href="{{ $user->getKycDocumentUrl() }}" target="_blank" class="text-indigo-600 dark:text-indigo-400 hover:underline text-xs font-bold flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <span>{{ __('Agrandir') }}</span>
                                    </a>
                                </div>
                            @else
                                <span class="text-gray-400 italic text-xs">{{ __('Aucun document') }}</span>
                            @endif
                        </td>
                        <td class="py-3 text-sm text-gray-500">
                            {{ $user->updated_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="py-3">
                            <div class="flex gap-2">
                                <button wire:click="verify({{ $user->id }}, 'verified')" class="bg-green-100 text-green-700 px-3 py-1 rounded text-xs font-bold hover:bg-green-200">{{ __('Approuver') }}</button>
                                <button wire:click="verify({{ $user->id }}, 'rejected')" class="bg-red-100 text-red-700 px-3 py-1 rounded text-xs font-bold hover:bg-red-200">{{ __('Rejeter') }}</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-gray-500 italic">{{ __('Aucune demande en attente.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $pendingUsers->links() }}
    </div>
</div>
