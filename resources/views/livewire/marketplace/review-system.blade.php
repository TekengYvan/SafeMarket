<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border dark:border-gray-700">
    @if($hasReviewed)
        <div class="text-center py-4">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-green-100 text-green-600 rounded-full mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <p class="font-bold text-gray-900 dark:text-white">{{ __('Avis envoyé avec succès !') }}</p>
            <p class="text-sm text-gray-500">{{ __('Merci d\'avoir partagé votre expérience sur Safemarket.') }}</p>
        </div>
    @else
        <h3 class="text-lg font-bold mb-4">{{ __('Laisser un avis') }}</h3>
        <form wire:submit.prevent="submitReview" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Note du Produit') }}</label>
                    <div class="flex gap-2">
                        @foreach([1,2,3,4,5] as $i)
                            <button type="button" wire:click="$set('productRating', {{ $i }})" class="focus:outline-none">
                                <svg class="w-8 h-8 {{ $productRating >= $i ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </button>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Note du Vendeur') }}</label>
                    <div class="flex gap-2">
                        @foreach([1,2,3,4,5] as $i)
                            <button type="button" wire:click="$set('vendorRating', {{ $i }})" class="focus:outline-none">
                                <svg class="w-8 h-8 {{ $vendorRating >= $i ? 'text-indigo-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Votre commentaire') }}</label>
                <textarea wire:model="comment" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-md shadow-sm" rows="3" placeholder="{{ __('Qu\'avez-vous pensé de cet achat ?') }}"></textarea>
            </div>

            <x-primary-button type="submit" class="w-full justify-center py-3 text-lg">{{ __('Publier mon avis') }}</x-primary-button>
        </form>
    @endif
</div>
