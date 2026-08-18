<div class="space-y-8">
    @if (session()->has('status'))
        <div class="p-4 bg-emerald-50/90 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900/60 rounded-3xl text-emerald-800 dark:text-emerald-300 font-bold text-sm flex items-center gap-3 shadow-sm transition">
            <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <!-- Inventory Header Hero Card -->
    <div class="relative overflow-hidden bg-gradient-to-r from-red-600 via-rose-600 to-amber-500 rounded-[2.5rem] p-7 sm:p-9 text-white shadow-xl shadow-red-500/10">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-20 top-0 w-32 h-32 bg-amber-300/20 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-xs font-extrabold uppercase tracking-widest text-white mb-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    <span>{{ __('Boutique En Ligne') }}</span>
                </div>
                <h3 class="text-3xl font-extrabold tracking-tight">{{ __('Catalogue de Vos Articles') }}</h3>
                <p class="text-white/80 text-sm mt-1 max-w-md">{{ __('Gérez vos stocks, ajustez vos prix et publiez de nouveaux produits en toute simplicité.') }}</p>
            </div>

            <button wire:click="openCreateModal" class="px-6 py-3.5 bg-white text-red-600 hover:bg-red-50 font-extrabold text-sm rounded-2xl shadow-xl transition-all duration-200 transform hover:-translate-y-0.5 flex items-center gap-2.5 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                <span>{{ __('Ajouter un produit') }}</span>
            </button>
        </div>
    </div>

    <!-- Centered Add/Edit Popup Modal -->
    <div x-show="$wire.showFormModal" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-950/70 backdrop-blur-md" wire:click="resetForm"></div>

        <!-- Modal Container -->
        <div class="relative bg-white dark:bg-gray-900 rounded-[2.5rem] border border-gray-150 dark:border-gray-800 p-6 sm:p-8 max-w-2xl w-full z-10 shadow-2xl max-h-[90vh] overflow-y-auto transform transition-all my-auto">
            <div class="flex items-center justify-between pb-4 mb-6 border-b border-gray-100 dark:border-gray-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 flex items-center justify-center shadow-inner">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-extrabold text-gray-900 dark:text-white">
                            {{ $isEditing ? __('Modifier le Produit') : __('Publier un Nouveau Produit') }}
                        </h3>
                        <p class="text-xs text-gray-400">{{ __('Remplissez les détails pour mettre votre article en vente.') }}</p>
                    </div>
                </div>

                <button type="button" wire:click="resetForm" class="p-2.5 rounded-xl text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                        {{ __('Titre du produit') }} <span class="text-red-500">*</span>
                    </label>
                    <input wire:model="title" id="title" type="text" required placeholder="{{ __('Ex: iPhone 13 Pro Max 128 Go Bleu') }}" class="w-full text-sm rounded-2xl border-gray-200 dark:border-gray-800 dark:bg-gray-950 text-gray-900 dark:text-white focus:border-red-500 focus:ring-red-500 shadow-sm px-4 py-3" />
                    <x-input-error :messages="$errors->get('title')" class="mt-1" />
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                        {{ __('Catégorie') }} <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="category_id" id="category_id" class="w-full text-sm rounded-2xl border-gray-200 dark:border-gray-800 dark:bg-gray-950 text-gray-900 dark:text-white focus:border-red-500 focus:ring-red-500 shadow-sm px-4 py-3">
                        <option value="">{{ __('Sélectionnez une catégorie') }}</option>
                        @foreach($categories as $parent)
                            @if($parent->children->isNotEmpty())
                                <optgroup label="{{ $parent->name }}">
                                    @foreach($parent->children as $sub)
                                        <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                                    @endforeach
                                </optgroup>
                            @else
                                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                            @endif
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('category_id')" class="mt-1" />
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                        {{ __('État de l\'article') }} <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="condition" id="condition" class="w-full text-sm rounded-2xl border-gray-200 dark:border-gray-800 dark:bg-gray-950 text-gray-900 dark:text-white focus:border-red-500 focus:ring-red-500 shadow-sm px-4 py-3">
                        <option value="new">{{ __('Neuf (Sous blister)') }}</option>
                        <option value="like_new">{{ __('Comme neuf (Impeccable)') }}</option>
                        <option value="good">{{ __('Bon état') }}</option>
                        <option value="fair">{{ __('État correct') }}</option>
                    </select>
                    <x-input-error :messages="$errors->get('condition')" class="mt-1" />
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                        {{ __('Description détaillée') }} <span class="text-red-500">*</span>
                    </label>
                    <textarea wire:model="description" id="description" rows="3" placeholder="{{ __('Décrivez les fonctionnalités, accessoires inclus, état de la batterie, garantie...') }}" class="w-full text-sm rounded-2xl border-gray-200 dark:border-gray-800 dark:bg-gray-950 text-gray-900 dark:text-white focus:border-red-500 focus:ring-red-500 shadow-sm px-4 py-3"></textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-1" />
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                        {{ __('Prix Standard (FCFA)') }} <span class="text-red-500">*</span>
                    </label>
                    <input wire:model="price" id="price" type="number" step="1" required placeholder="{{ __('Ex: 450000') }}" class="w-full text-sm rounded-2xl border-gray-200 dark:border-gray-800 dark:bg-gray-950 text-gray-900 dark:text-white focus:border-red-500 focus:ring-red-500 shadow-sm px-4 py-3" />
                    <x-input-error :messages="$errors->get('price')" class="mt-1" />
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                        {{ __('Localisation (Ville / Quartier)') }} <span class="text-red-500">*</span>
                    </label>
                    <input wire:model="location" id="location" type="text" required placeholder="{{ __('Ex: Douala (Akwa)') }}" class="w-full text-sm rounded-2xl border-gray-200 dark:border-gray-800 dark:bg-gray-950 text-gray-900 dark:text-white focus:border-red-500 focus:ring-red-500 shadow-sm px-4 py-3" />
                    <x-input-error :messages="$errors->get('location')" class="mt-1" />
                </div>

                <!-- Promotion Box -->
                <div class="p-5 bg-gradient-to-br from-red-50/70 to-rose-50/40 dark:from-red-950/30 dark:to-rose-950/20 rounded-3xl border border-red-200/80 dark:border-red-900/40 md:col-span-2 space-y-3">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="is_on_sale" class="w-4 h-4 rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                        <span class="ms-2.5 text-xs font-extrabold text-red-600 dark:text-red-400 uppercase tracking-wider flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                            <span>{{ __('Activer une Promotion / Solde') }}</span>
                        </span>
                    </label>

                    @if($is_on_sale)
                        <div class="pt-2">
                            <label class="block text-xs font-bold text-red-700 dark:text-red-300 uppercase tracking-wider mb-1">
                                {{ __('Prix Réduit Spécial Promotion (FCFA)') }} <span class="text-red-500">*</span>
                            </label>
                            <input wire:model="discount_price" id="discount_price" type="number" step="1" placeholder="{{ __('Ex: 400000') }}" class="w-full text-sm rounded-2xl border-red-300 dark:border-red-900/60 dark:bg-gray-950 text-gray-900 dark:text-white focus:border-red-500 focus:ring-red-500 shadow-sm px-4 py-3" />
                            <x-input-error :messages="$errors->get('discount_price')" class="mt-1" />
                        </div>
                    @endif
                </div>

                <div class="flex flex-wrap items-center gap-6 md:col-span-2 p-4 bg-gray-50/80 dark:bg-gray-950/50 rounded-2xl border border-gray-150 dark:border-gray-800">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="is_in_stock" class="w-4 h-4 rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                        <span class="ms-2 text-xs font-bold text-gray-700 dark:text-gray-300 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            <span>{{ __('Disponible en Stock') }}</span>
                        </span>
                    </label>

                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="has_invoice" class="w-4 h-4 rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500">
                        <span class="ms-2 text-xs font-bold text-emerald-700 dark:text-emerald-400 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            <span>{{ __('Facture Certifiée Fournie') }}</span>
                        </span>
                    </label>
                </div>

                @if($has_invoice)
                    <div class="md:col-span-2 bg-emerald-50/70 dark:bg-emerald-950/30 p-4 rounded-2xl border border-emerald-200 dark:border-emerald-900/40">
                        <label class="block text-xs font-bold text-emerald-800 dark:text-emerald-300 uppercase tracking-wider mb-1">
                            {{ __('Document Facture (PDF, JPG, PNG)') }}
                        </label>
                        <input type="file" wire:model="invoiceFile" id="invoiceFile" class="block mt-1 w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-100 file:text-emerald-800 hover:file:bg-emerald-200" />
                        <x-input-error :messages="$errors->get('invoiceFile')" class="mt-1" />
                    </div>
                @endif

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                        {{ $isEditing ? __('Ajouter de nouvelles photos (optionnel)') : __('Photos du produit (JPG, PNG, WEBP)') }}
                    </label>
                    <input type="file" wire:model="images" id="images" multiple class="block mt-1 w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-2xl file:border-0 file:text-xs file:font-extrabold file:bg-red-50 file:text-red-600 hover:file:bg-red-100 dark:file:bg-red-950/40 dark:file:text-red-400 cursor-pointer" />
                    <x-input-error :messages="$errors->get('images')" class="mt-1" />
                </div>

                <div class="md:col-span-2 flex gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                    <button type="button" wire:click="resetForm" class="flex-1 py-3.5 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold text-xs uppercase tracking-wider rounded-2xl transition">
                        {{ __('Annuler') }}
                    </button>
                    <button type="submit" class="flex-1 py-3.5 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white font-extrabold text-xs uppercase tracking-wider rounded-2xl shadow-lg shadow-red-500/20 transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>{{ $isEditing ? __('Sauvegarder les modifications') : __('Publier le produit') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($products as $product)
            <div class="group bg-white dark:bg-gray-900 border border-gray-200/70 dark:border-gray-800/80 rounded-[2rem] overflow-hidden flex flex-col justify-between shadow-sm hover:shadow-xl hover:border-red-500/30 transition-all duration-300">
                <div>
                    <!-- Image Card with Badges -->
                    <div class="h-52 bg-gray-100 dark:bg-gray-950 relative overflow-hidden">
                        <img src="{{ $product->getImageUrl() }}" 
                             alt="{{ $product->title }}"
                             onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&auto=format&fit=crop&q=80';"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        
                        <div class="absolute top-3.5 left-3.5 flex flex-col gap-1.5 z-10">
                            <span class="px-3 py-1 text-[10px] font-extrabold rounded-full uppercase tracking-wider shadow-sm {{ $product->status == 'available' ? 'bg-emerald-500 text-white' : 'bg-gray-800 text-white' }}">
                                {{ $product->status == 'available' ? __('Disponible') : $product->status }}
                            </span>
                            @if($product->is_on_sale)
                                <span class="px-2.5 py-1 text-[10px] font-extrabold rounded-full uppercase tracking-wider bg-red-600 text-white shadow-md shadow-red-500/30 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                    <span>{{ __('PROMO') }}</span>
                                </span>
                            @endif
                        </div>

                        @if($product->has_invoice)
                            <div class="absolute top-3.5 right-3.5 z-10 bg-white/90 dark:bg-gray-900/90 backdrop-blur-md px-2.5 py-1 rounded-full text-[10px] font-extrabold text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 shadow-sm flex items-center gap-1">
                                <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                <span>{{ __('Facture') }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="p-6">
                        <div class="text-[11px] font-bold text-red-600 dark:text-red-400 uppercase tracking-wider mb-1">
                            {{ $product->category->name ?? __('Catégorie') }}
                        </div>

                        <h4 class="font-extrabold text-gray-900 dark:text-white text-base truncate mb-2 group-hover:text-red-600 transition-colors" title="{{ $product->title }}">
                            {{ $product->title }}
                        </h4>
                        
                        <!-- Price Block -->
                        <div class="flex items-baseline gap-2 mt-2">
                            @if($product->is_on_sale && $product->discount_price > 0)
                                <span class="text-2xl font-black text-red-600 dark:text-red-400 tracking-tight">
                                    {{ number_format($product->discount_price, 0, ',', ' ') }} <span class="text-xs font-bold">FCFA</span>
                                </span>
                                <span class="text-xs text-gray-400 line-through font-semibold">
                                    {{ number_format($product->price, 0, ',', ' ') }} FCFA
                                </span>
                            @else
                                <span class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">
                                    {{ number_format($product->price, 0, ',', ' ') }} <span class="text-xs font-bold text-gray-400">FCFA</span>
                                </span>
                            @endif
                        </div>

                        <div class="mt-3 flex items-center gap-2 text-xs text-gray-400 dark:text-gray-500">
                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                            <span class="truncate">{{ $product->location }}</span>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="p-4 px-6 border-t border-gray-100 dark:border-gray-800/80 flex justify-between items-center bg-gray-50/50 dark:bg-gray-950/40">
                    <button wire:click="edit({{ $product->id }})" class="inline-flex items-center gap-1.5 text-xs font-extrabold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        <span>{{ __('Modifier') }}</span>
                    </button>

                    <button onclick="confirm('{{ __('Voulez-vous vraiment supprimer ce produit ?') }}') || event.stopImmediatePropagation()" wire:click="delete({{ $product->id }})" class="inline-flex items-center gap-1.5 text-xs font-extrabold text-rose-600 dark:text-rose-400 hover:text-rose-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        <span>{{ __('Supprimer') }}</span>
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-16 bg-white dark:bg-gray-900 rounded-[2.5rem] border border-dashed border-gray-200 dark:border-gray-800">
                <div class="w-16 h-16 mx-auto mb-4 rounded-3xl bg-red-50 dark:bg-red-950/30 text-red-500 flex items-center justify-center shadow-inner">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <h4 class="text-base font-bold text-gray-900 dark:text-white mb-1">{{ __('Votre boutique est vide pour l\'instant') }}</h4>
                <p class="text-gray-400 text-xs max-w-sm mx-auto mb-6">{{ __('Commencez par ajouter votre premier produit pour qu\'il apparaisse sur la marketplace.') }}</p>
                <button wire:click="openCreateModal" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold text-xs uppercase tracking-wider rounded-2xl shadow-lg shadow-red-500/20 transition">
                    {{ __('+ Publier mon premier article') }}
                </button>
            </div>
        @endforelse
    </div>
</div>

