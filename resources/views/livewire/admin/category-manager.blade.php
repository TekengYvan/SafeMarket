<div class="bg-white dark:bg-gray-900 p-6 rounded-3xl border border-gray-200/50 dark:border-gray-800/80 shadow-sm">
    <h3 class="text-xl font-black text-gray-900 dark:text-white mb-6">{{ __('Gestion des Catégories') }}</h3>

    @if (session()->has('status'))
        <div class="mb-4 p-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-450 border border-emerald-250 dark:border-emerald-900/50 rounded-2xl text-sm font-semibold">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit.prevent="store" class="flex flex-col md:flex-row gap-4 mb-8">
        <div class="flex-grow">
            <x-text-input wire:model="name" placeholder="{{ __('Nom de la catégorie...') }}" class="w-full text-sm" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
        <div class="w-full md:w-64">
            <select wire:model="parent_id" class="border-gray-200 dark:border-gray-800 dark:bg-gray-950 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm w-full h-11 text-sm text-gray-700 dark:text-gray-300">
                <option value="">{{ __('-- Catégorie principale (Aucun parent) --') }}</option>
                @foreach($parentCategories as $parent)
                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('parent_id')" class="mt-2" />
        </div>
        <x-primary-button type="submit" class="justify-center h-11 px-6 rounded-xl">{{ __('Ajouter') }}</x-primary-button>
    </form>

    <div class="space-y-4">
        @foreach($categories as $category)
            <div class="bg-gray-50/50 dark:bg-gray-850/20 p-5 rounded-2xl border border-gray-250/50 dark:border-gray-800/50">
                <div class="flex justify-between items-center pb-3 border-b border-gray-200/50 dark:border-gray-800/50 mb-3">
                    <span class="font-extrabold text-gray-900 dark:text-white text-base flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                        {{ $category->name }}
                    </span>
                    <button onclick="confirm('Supprimer cette catégorie principale ainsi que ses sous-catégories ?') || event.stopImmediatePropagation()" wire:click="delete({{ $category->id }})" class="text-rose-500 hover:text-rose-700 p-1.5 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-950/20 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>

                @if($category->children->isNotEmpty())
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 ml-2 md:ml-6">
                        @foreach($category->children as $sub)
                            <div class="flex justify-between items-center bg-white dark:bg-gray-950 px-4 py-2.5 rounded-xl border border-gray-150 dark:border-gray-800/80 shadow-sm">
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    {{ $sub->name }}
                                </span>
                                <button onclick="confirm('Supprimer cette sous-catégorie ?') || event.stopImmediatePropagation()" wire:click="delete({{ $sub->id }})" class="text-rose-500 hover:text-rose-700 transition p-1 rounded hover:bg-rose-50 dark:hover:bg-rose-950/20">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-gray-400 dark:text-gray-500 italic ml-2 md:ml-6">{{ __('Aucune sous-catégorie.') }}</p>
                @endif
            </div>
        @endforeach
    </div>
</div>
