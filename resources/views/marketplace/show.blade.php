<x-safemarket-layout>
    <x-slot name="title">{{ $product->title }} - SafeMarket</x-slot>

    <div class="py-8 bg-gray-50/50 dark:bg-gray-950/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back navigation link -->
            <div class="mb-6">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span>{{ __('Retour au Marketplace') }}</span>
                </a>
            </div>

            <!-- Product Card Wrapper -->
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-sm rounded-3xl border border-gray-200/50 dark:border-gray-800/80 p-6 md:p-8">
                @php
                    $mediaUrls = [];
                    if ($product->hasMedia('products')) {
                        foreach($product->getMedia('products') as $media) {
                            $mediaUrls[] = $media->getUrl();
                        }
                    } elseif ($product->images->isNotEmpty()) {
                        foreach($product->images as $img) {
                            $mediaUrls[] = Storage::url($img->image_path);
                        }
                    }
                    if (empty($mediaUrls)) {
                        $mediaUrls[] = $product->getImageUrl();
                    }
                @endphp

                <div class="flex flex-col lg:flex-row gap-10" x-data="{ activeImage: '{{ $mediaUrls[0] ?? '' }}' }">
                    <!-- Image Gallery -->
                    <div class="w-full lg:w-1/2">
                        <!-- Main Image Container -->
                        <div class="aspect-square bg-gray-50 dark:bg-gray-950 rounded-2xl overflow-hidden mb-4 border border-gray-100 dark:border-gray-800/50 relative flex items-center justify-center">
                            @if(count($mediaUrls) > 0)
                                <img :src="activeImage" alt="{{ $product->title }}" class="w-full h-full object-cover transition-all duration-300">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-gray-300 dark:text-gray-600">
                                    <svg class="w-20 h-20 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-sm font-semibold">{{ __('Aucune image disponible') }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Thumbnails Grid -->
                        @if(count($mediaUrls) > 1)
                            <div class="grid grid-cols-4 gap-3">
                                @foreach($mediaUrls as $url)
                                    <button @click="activeImage = '{{ $url }}'" class="aspect-square bg-gray-50 dark:bg-gray-950 rounded-xl overflow-hidden border-2 transition-all duration-200 focus:outline-none" :class="activeImage === '{{ $url }}' ? 'border-red-600 dark:border-red-400 scale-95 shadow-sm' : 'border-transparent hover:border-gray-200 dark:hover:border-gray-800'">
                                        <img src="{{ $url }}" class="w-full h-full object-cover">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Product Info -->
                    <div class="w-full lg:w-1/2 flex flex-col justify-between">
                        <div>
                            <!-- Tags and Statuses -->
                            <div class="flex flex-wrap gap-2 mb-4 items-center">
                                <span class="bg-red-50 dark:bg-red-950/20 text-red-600 dark:text-red-400 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">{{ $product->category->name }}</span>
                                <span class="bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">{{ $product->condition }}</span>
                                
                                @if($product->is_in_stock)
                                    <span class="bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        {{ __('En stock') }}
                                    </span>
                                @else
                                    <span class="bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-400 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        {{ __('Hors stock') }}
                                    </span>
                                @endif
                            </div>

                            <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight mb-4">{{ $product->title }}</h1>

                            <!-- Invoice block -->
                            @if($product->has_invoice && $product->hasMedia('invoices'))
                                <div class="mb-6">
                                    <a href="{{ $product->getFirstMediaUrl('invoices') }}" target="_blank" class="inline-flex items-center gap-3.5 p-3.5 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-900/50 rounded-2xl text-emerald-700 dark:text-emerald-400 hover:bg-emerald-100/70 dark:hover:bg-emerald-950/30 transition w-full group">
                                        <div class="p-2 bg-emerald-500 text-white rounded-xl shadow-md group-hover:scale-105 transition-transform">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-sm">{{ __('Facture Certifiée Disponible') }}</p>
                                            <p class="text-xs text-emerald-600/80 dark:text-emerald-400/80">{{ __('Cliquer pour consulter le document officiel vérifié') }}</p>
                                        </div>
                                    </a>
                                </div>
                            @endif

                            @php
                                $acceptedNegotiation = auth()->check() ? \App\Models\Negotiation::where('product_id', $product->id)
                                    ->where('buyer_id', auth()->id())
                                    ->where('status', 'accepted')
                                    ->first() : null;
                            @endphp

                            <!-- Price Display -->
                            @if($acceptedNegotiation)
                                <div class="mb-6 bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-950/20 dark:to-teal-950/20 border border-emerald-200 dark:border-emerald-900/50 p-5 rounded-2xl flex items-center justify-between shadow-sm">
                                    <div>
                                        <p class="text-xs text-emerald-700 dark:text-emerald-400 font-extrabold uppercase tracking-wider mb-1 flex items-center gap-1">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                            {{ __('Prix négocié accepté !') }}
                                        </p>
                                        <p class="text-3xl font-black text-emerald-600 dark:text-emerald-400">{{ number_format($acceptedNegotiation->proposed_price, 2) }} FCFA</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-wide">{{ __('Prix initial') }}</p>
                                        <p class="text-lg font-bold text-gray-400 dark:text-gray-500 line-through">
                                            {{ number_format($product->price, 2) }} FCFA
                                        </p>
                                    </div>
                                </div>
                            @else
                                <div class="mb-6">
                                    <span class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wider block mb-1">{{ __('Prix de vente') }}</span>
                                    <p class="text-4xl font-black text-red-650 dark:text-red-400 tracking-tight">{{ number_format($product->price, 2) }} FCFA</p>
                                </div>
                            @endif

                            <!-- Description Block -->
                            <div class="border-t border-gray-100 dark:border-gray-800/80 pt-5 pb-2 mb-6">
                                <h4 class="font-bold text-gray-900 dark:text-white mb-2">{{ __('Description') }}</h4>
                                <p class="text-gray-600 dark:text-gray-300 whitespace-pre-line text-sm leading-relaxed">{{ $product->description }}</p>
                            </div>

                            <!-- Vendor Trust Card -->
                            <div class="mb-6 flex items-center gap-4 p-4 rounded-2xl bg-red-50/30 dark:bg-red-950/10 border border-red-100/50 dark:border-red-900/30">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-red-500 to-amber-600 flex items-center justify-center font-extrabold text-white text-lg shadow-md shadow-red-200 dark:shadow-none shrink-0">
                                    {{ substr($product->vendor->name, 0, 1) }}
                                </div>
                                <div class="flex-grow min-w-0">
                                    <p class="font-bold text-gray-900 dark:text-gray-100 truncate">{{ __('Vendeur') }} : {{ $product->vendor->name }}</p>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('Score de confiance') }} :</span>
                                        <span class="text-xs font-extrabold text-emerald-600 dark:text-emerald-400">{{ $product->vendor->trust_score }}/100</span>
                                    </div>
                                    <!-- Trust Score Progress bar -->
                                    <div class="w-full bg-gray-200 dark:bg-gray-800 rounded-full h-1.5 mt-1.5 overflow-hidden">
                                        <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $product->vendor->trust_score }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions Block -->
                        <div>
                            @auth
                                @if($product->vendor_id !== auth()->id())
                                    <form action="{{ route('cart.store') }}" method="POST" class="mb-4">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 text-white text-base font-bold py-4 rounded-2xl shadow-lg shadow-red-100 dark:shadow-none hover:shadow-red-200 dark:hover:shadow-none transition duration-200 flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                            {{ __('Ajouter au panier') }}
                                        </button>
                                    </form>

                                    <!-- Price negotiation section -->
                                    <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-800/80">
                                        <h4 class="font-bold text-gray-900 dark:text-white mb-3 text-sm flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                            {{ __('Négocier le prix') }}
                                        </h4>
                                        <form action="{{ route('negotiations.store') }}" method="POST" class="bg-gray-50/50 dark:bg-gray-850/30 p-5 rounded-2xl border border-gray-200/50 dark:border-gray-800/50">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            
                                            <div class="mb-4">
                                                <label for="proposed_price" class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">{{ __('Votre proposition (FCFA)') }}</label>
                                                <input id="proposed_price" type="number" step="0.01" name="proposed_price" required placeholder="Ex: {{ $product->price * 0.9 }}" class="border-gray-200 dark:border-gray-800 dark:bg-gray-950 focus:border-red-500 focus:ring-red-500 rounded-xl shadow-sm block w-full text-sm" />
                                            </div>
                                            
                                            <div class="mb-4">
                                                <label for="message" class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">{{ __('Message au vendeur') }}</label>
                                                <textarea id="message" name="message" rows="2" class="border-gray-200 dark:border-gray-800 dark:bg-gray-950 focus:border-red-500 focus:ring-red-500 rounded-xl shadow-sm block w-full text-sm placeholder:text-gray-400" placeholder="{{ __('Pourquoi ce prix ? (ex: Achat immédiat, lot, etc.)') }}"></textarea>
                                            </div>
                                            
                                            <button type="submit" class="w-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700/80 text-gray-800 dark:text-gray-200 text-sm font-bold py-2.5 rounded-xl transition duration-200 flex items-center justify-center gap-1.5">
                                                🤝 {{ __('Envoyer la proposition') }}
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <div class="bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900/50 text-amber-800 dark:text-amber-400 p-4 rounded-2xl text-center font-semibold text-sm italic">
                                        {{ __('C\'est votre produit !') }}
                                    </div>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="w-full block text-center bg-gray-900 hover:bg-gray-800 dark:bg-gray-800 dark:hover:bg-gray-700 text-white text-base font-bold py-4 rounded-2xl shadow-lg transition duration-200">
                                    {{ __('Connectez-vous pour acheter') }}
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ratings & Reviews Section -->
            <div class="mt-8 bg-white dark:bg-gray-900 rounded-3xl border border-gray-200/50 dark:border-gray-800/80 p-6 md:p-8 shadow-sm space-y-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-6 border-b border-gray-100 dark:border-gray-800">
                    <div>
                        <h3 class="text-xl font-extrabold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-6 h-6 text-amber-400 fill-amber-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <span>{{ __('Avis et Évaluations') }} ({{ $product->reviews_count }})</span>
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ __('Retours d\'expérience des acheteurs vérifiés sur ce produit.') }}</p>
                    </div>

                    <div class="flex items-center gap-3 bg-amber-50 dark:bg-amber-950/30 px-4 py-2 rounded-2xl border border-amber-200/60 dark:border-amber-900/40">
                        <span class="text-2xl font-black text-amber-700 dark:text-amber-400">{{ $product->average_rating }}</span>
                        <div class="flex text-amber-400">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= round($product->average_rating) ? 'fill-amber-400' : 'text-gray-300 dark:text-gray-700' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                    </div>
                </div>

                <!-- Review Form for verified buyers -->
                @if(isset($completedOrder) && $completedOrder)
                    <div class="mb-6">
                        <livewire:marketplace.review-system :order="$completedOrder" />
                    </div>
                @endif

                <!-- Reviews List -->
                <div class="space-y-4">
                    @forelse($product->reviews as $review)
                        <div class="p-4 bg-gray-50/70 dark:bg-gray-950/40 rounded-2xl border border-gray-150 dark:border-gray-800/60">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $review->buyer->getAvatarUrl() }}" alt="{{ $review->buyer->name }}" class="w-8 h-8 rounded-full object-cover border border-red-500 shrink-0">
                                    <div>
                                        <span class="font-extrabold text-xs text-gray-900 dark:text-white block">{{ $review->buyer->name }}</span>
                                        <span class="text-[10px] text-gray-400">{{ $review->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                                <div class="flex text-amber-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-3.5 h-3.5 {{ $i <= $review->product_rating ? 'fill-amber-400' : 'text-gray-300 dark:text-gray-700' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </div>
                            </div>
                            @if($review->comment)
                                <p class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed mt-2 pl-11">{{ $review->comment }}</p>
                            @endif
                        </div>
                    @empty
                        <div class="py-8 text-center text-gray-400 italic text-xs">
                            {{ __('Aucun avis déposé pour ce produit pour le moment.') }}
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-safemarket-layout>
