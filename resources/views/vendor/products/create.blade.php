<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ajouter un produit') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                <form action="{{ route('vendor.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <x-input-label for="title" :value="__('Titre du produit')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                        </div>
                        <div>
                            <x-input-label for="category_id" :value="__('Catégorie')" />
                            <select id="category_id" name="category_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full text-sm">
                                @foreach($categories as $parent)
                                    @if($parent->children->isNotEmpty())
                                        <optgroup label="{{ $parent->name }}">
                                            @foreach($parent->children as $sub)
                                                <option value="{{ $sub->id }}" {{ old('category_id') == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @else
                                        <option value="{{ $parent->id }}" {{ old('category_id') == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="price" :value="__('Prix (€)')" />
                            <x-text-input id="price" class="block mt-1 w-full" type="number" step="0.01" name="price" :value="old('price')" required />
                        </div>
                        <div>
                            <x-input-label for="condition" :value="__('État')" />
                            <select id="condition" name="condition" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                                <option value="new">{{ __('Neuf') }}</option>
                                <option value="like_new">{{ __('Comme neuf') }}</option>
                                <option value="good">{{ __('Bon état') }}</option>
                                <option value="fair">{{ __('État moyen') }}</option>
                            </select>
                        </div>
                        <div>
                            <x-input-label for="location" :value="__('Localisation (Ville)')" />
                            <x-text-input id="location" class="block mt-1 w-full" type="text" name="location" :value="old('location')" required />
                        </div>
                        <div class="flex items-center mt-6">
                            <input type="checkbox" name="is_in_stock" id="is_in_stock" value="1" checked class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <label for="is_in_stock" class="ms-2 text-sm text-gray-600">{{ __('En stock') }}</label>
                        </div>
                        <div>
                            <x-input-label for="images" :value="__('Images du produit')" />
                            <input type="file" name="images[]" multiple class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                    </div>

                    <div class="mb-6">
                        <x-input-label for="description" :value="__('Description détaillée')" />
                        <textarea id="description" name="description" rows="5" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>{{ old('description') }}</textarea>
                    </div>

                    <div class="flex items-center justify-end">
                        <a href="{{ route('vendor.products.index') }}" class="text-gray-600 hover:text-gray-900 me-4">{{ __('Annuler') }}</a>
                        <x-primary-button>
                            {{ __('Publier le produit') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
