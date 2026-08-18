<x-safemarket-layout>
    <x-slot name="title">{{ __('Marketplace - SafeMarket') }}</x-slot>

    <div class="py-8 bg-gray-50/50 dark:bg-gray-950/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8 p-6 md:p-8 bg-zinc-900 rounded-3xl text-white shadow-lg relative overflow-hidden">
                <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px] pointer-events-none"></div>
                <div class="relative z-10 max-w-xl">
                    <span class="text-red-500 font-heading font-black tracking-widest text-xs uppercase block">{{ app()->getLocale() == 'en' ? 'Find & Negotiate' : 'Trouvez et Négociez' }}</span>
                    <h1 class="text-2xl md:text-3xl font-extrabold uppercase mt-1 mb-2">{{ app()->getLocale() == 'en' ? 'Thousands of verified products live' : 'Des milliers de produits vérifiés en direct' }}</h1>
                    <p class="text-stone-300 text-sm font-medium">{{ app()->getLocale() == 'en' ? 'Buy with confidence using our secure ESCROW payment system.' : 'Achetez en toute confiance avec notre système de paiement sécurisé en ESCROW.' }}</p>
                </div>
                <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-red-600/10 rounded-full blur-2xl"></div>
                <div class="absolute -left-10 -top-10 w-48 h-48 bg-red-600/10 rounded-full blur-2xl"></div>
            </div>

            <div class="flex flex-col md:flex-row gap-8">
                <!-- Sidebar Filters -->
                <div class="w-full md:w-1/4">
                    <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-200/60 dark:border-gray-800/80 shadow-sm sticky top-24">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-4">{{ __('Catégories') }}</h3>
                        <div class="space-y-1.5">
                            <a href="{{ route('home') }}" class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ !request('category') ? 'bg-red-50 dark:bg-red-950/20 text-red-600 dark:text-red-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800/40 hover:text-gray-900 dark:hover:text-gray-200' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                                <span>{{ __('Toutes') }}</span>
                            </a>
                            @foreach($categories as $cat)
                                <div class="space-y-1">
                                    <a href="{{ route('home', ['category' => $cat->slug]) }}" class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request('category') == $cat->slug ? 'bg-red-50 dark:bg-red-950/20 text-red-600 dark:text-red-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-850/40 hover:text-gray-900 dark:hover:text-gray-200' }}">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        <span>{{ $cat->name }}</span>
                                    </a>

                                    @if($cat->children->isNotEmpty())
                                        <div class="pl-6 space-y-1">
                                            @foreach($cat->children as $sub)
                                                <a href="{{ route('home', ['category' => $sub->slug]) }}" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium transition-all {{ request('category') == $sub->slug ? 'text-red-650 dark:text-red-400 font-bold' : 'text-gray-550 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-250' }}">
                                                    <span>•</span>
                                                    <span>{{ $sub->name }}</span>
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Products Section -->
                <div class="w-full md:w-3/4">
                    <!-- Search bar -->
                    <form action="{{ route('home') }}" method="GET" class="flex gap-4 mb-6">
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        <div class="relative flex-grow">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" name="search" placeholder="{{ __('Que recherchez-vous ?') }}" class="pl-11 border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:border-red-500 focus:ring-red-500 rounded-2xl shadow-sm w-full h-12 transition-all text-sm" value="{{ request('search') }}">
                        </div>
                        <button type="submit" class="bg-red-600 hover:bg-red-750 dark:bg-red-500 dark:hover:bg-red-600 text-white font-semibold px-6 py-3 rounded-2xl shadow-md transition text-sm flex items-center gap-1.5">
                            <span>{{ __('Chercher') }}</span>
                        </button>
                    </form>

                    <!-- Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($products as $product)
                            @auth
                                <a href="{{ route('products.show', $product) }}" class="group bg-white dark:bg-gray-900 rounded-2xl border border-gray-200/50 dark:border-gray-800/80 overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between">
                            @else
                                <a href="javascript:void(0)" @click="showAuthModal = true" class="group bg-white dark:bg-gray-900 rounded-2xl border border-gray-200/50 dark:border-gray-800/80 overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between">
                            @endauth
                                <div>
                                    <!-- Image and overlays -->
                                    <div class="relative h-48 bg-gray-100 dark:bg-gray-950 overflow-hidden">
                                        <img src="{{ $product->getImageUrl() }}" alt="{{ $product->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">

                                        <!-- Badge overlay Category -->
                                        <div class="absolute top-3.5 left-3.5">
                                            <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider bg-white/90 dark:bg-gray-900/90 text-red-600 dark:text-red-400 rounded-full backdrop-blur shadow-sm">
                                                {{ $product->category->name }}
                                            </span>
                                        </div>

                                        <!-- Invoice Badge -->
                                        @if($product->has_invoice)
                                            <div class="absolute bottom-3.5 right-3.5">
                                                <span class="px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wide bg-emerald-500 text-white rounded-md shadow-sm">
                                                    {{ __('Facture certifiée') }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Content -->
                                    <div class="p-5">
                                        <h4 class="font-bold text-gray-900 dark:text-gray-100 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors text-base line-clamp-1 mb-2">{{ $product->title }}</h4>
                                        
                                        <div class="flex justify-between items-center mt-2 mb-1">
                                            <span class="text-xl font-extrabold text-red-600 dark:text-red-500">{{ number_format($product->price, 2) }} FCFA</span>
                                            <span class="px-2 py-0.5 text-[10px] font-bold bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 rounded uppercase">
                                                {{ $product->condition }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="px-5 pb-5 pt-0 border-t border-gray-100 dark:border-gray-800/80">
                                    <div class="flex justify-between items-center mt-4">
                                        <div>
                                            @if($product->is_in_stock)
                                                <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 dark:text-emerald-400 animate-pulse">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                    {{ __('En stock') }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 text-xs font-bold text-rose-600 dark:text-rose-450">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                    {{ __('Hors stock') }}
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-red-600 dark:text-red-450 font-bold inline-flex items-center gap-0.5 group-hover:translate-x-0.5 transition-transform text-xs">
                                            {{ __('Voir') }}
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="col-span-full text-center py-16 bg-white dark:bg-gray-900 rounded-3xl border border-gray-200/50 dark:border-gray-800/50">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-gray-500 font-medium">{{ __('Aucun produit trouvé.') }}</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-10">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-safemarket-layout>
