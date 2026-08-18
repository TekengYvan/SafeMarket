<div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
    <h3 class="text-xl font-bold mb-4">{{ __('Vérification d\'identité (KYC)') }}</h3>
    
    @if($status === 'verified')
        <div class="bg-green-100 text-green-800 p-4 rounded-lg flex items-center gap-3">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg>
            <span class="font-bold">{{ __('Votre compte est vérifié. Vous pouvez vendre des produits.') }}</span>
        </div>
    @elseif($status === 'pending')
        <div class="bg-yellow-100 text-yellow-800 p-4 rounded-lg flex items-center gap-3">
            <svg class="w-6 h-6 animate-pulse" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/></svg>
            <span class="font-bold">{{ __('Vérification en cours... Un administrateur examine votre document.') }}</span>
        </div>
    @else
        @if($status === 'rejected')
            <div class="bg-red-100 text-red-800 p-4 rounded-lg mb-4 flex items-center gap-3">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/></svg>
                <span class="font-bold">{{ __('Votre document a été rejeté. Veuillez en envoyer un nouveau bien lisible.') }}</span>
            </div>
        @endif

        <p class="text-sm text-gray-500 mb-6">{{ __('Pour devenir vendeur, vous devez envoyer une photo de votre pièce d\'identité (CNI ou Passeport).') }}</p>

        <form wire:submit.prevent="submit" class="space-y-4">
            <div>
                <x-input-label for="idCard" :value="__('Photo de votre pièce d\'identité')" />
                <input type="file" wire:model="idCard" id="idCard" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                <x-input-error :messages="$errors->get('idCard')" class="mt-2" />
                
                @if ($idCard)
                    <div class="mt-4">
                        <p class="text-xs text-gray-500 mb-2">{{ __('Aperçu') }} :</p>
                        <img src="{{ $idCard->temporaryUrl() }}" class="w-48 rounded-lg shadow-sm">
                    </div>
                @endif
            </div>

            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-indigo-700 transition" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ __('Envoyer pour vérification') }}</span>
                <span wire:loading>{{ __('Envoi en cours...') }}</span>
            </button>
        </form>
    @endif
</div>
