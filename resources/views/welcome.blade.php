<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth"
      x-data="{ 
          showAuthModal: false, 
          searchOpen: false, 
          searchQuery: '',
          newsletterEmail: '',
          newsletterSubscribed: false,
          blogToastOpen: false,
          scrolled: false
      }" 
      x-init="scrolled = (window.pageYOffset > 20)"
      :class="{ 'dark': $store.theme.darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ __('SafeMarket - Streetwear Style & Secure Escrow') }}</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@600;700;800;900&display=swap" rel="stylesheet" />

        <!-- Tailwind / Vite -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
            <script>
                tailwind.config = {
                    darkMode: 'class',
                    theme: {
                        extend: {
                            fontFamily: {
                                sans: ['Inter', 'sans-serif'],
                                heading: ['Outfit', 'sans-serif'],
                            },
                        }
                    }
                }
            </script>
        @endif
        
        <!-- Alpine.js -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.store('theme', {
                    darkMode: localStorage.getItem('dark') === 'true',
                    toggle() {
                        this.darkMode = !this.darkMode;
                        localStorage.setItem('dark', this.darkMode);
                    }
                });
            });
            if (localStorage.getItem('dark') === 'true') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

        <style>
            [x-cloak] { display: none !important; }
            .bg-striz-dark {
                background-color: #0f172a;
            }
            .text-striz-red {
                color: #E53E3E;
            }
            .bg-striz-red {
                background-color: #E53E3E;
            }
            .hover-striz-red:hover {
                background-color: #C53030;
            }
        </style>
    </head>
    <body class="antialiased bg-stone-50 text-stone-900 dark:bg-zinc-950 dark:text-zinc-100 transition-colors duration-300"
          @scroll.window="scrolled = (window.pageYOffset > 20)">

        <!-- Header (Striz Style) -->
        <header :class="{ 'bg-white/95 dark:bg-zinc-900/95 shadow-md py-3': scrolled, 'bg-white dark:bg-zinc-900 py-5': !scrolled }" class="fixed top-0 w-full z-40 transition-all duration-300 border-b border-stone-200/50 dark:border-zinc-800/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <!-- Left: Logo -->
                    <a href="{{ route('landing') }}" class="flex items-center gap-1.5 group">
                        <span class="font-heading font-black text-3xl tracking-tight text-stone-900 dark:text-white group-hover:scale-105 transition-transform duration-200 italic">
                            SafeMarket<span class="text-red-500 font-sans font-light">.</span>
                        </span>
                    </a>
                    
                    <!-- Center: Menu links -->
                    <nav class="hidden lg:flex gap-8 items-center font-heading text-xs font-black tracking-widest text-stone-700 dark:text-stone-300">
                        <a href="{{ route('landing') }}" class="text-red-600 dark:text-red-500 hover:text-red-750 transition-colors uppercase">{{ app()->getLocale() == 'en' ? 'Home' : 'Accueil' }}</a>
                        <a href="{{ route('home') }}" class="hover:text-red-500 transition-colors uppercase">{{ app()->getLocale() == 'en' ? 'Shop' : 'Marché' }}</a>
                        <a href="{{ route('products.spotlight') }}" class="hover:text-red-550 transition-colors uppercase">{{ app()->getLocale() == 'en' ? 'Product' : 'Produit' }}</a>
                        <a href="{{ route('pages.on-sale') }}" class="hover:text-red-550 transition-colors uppercase">{{ app()->getLocale() == 'en' ? 'On Sale' : 'En Promo' }}</a>
                        <a href="{{ route('pages.blog') }}" class="hover:text-red-550 transition-colors uppercase">{{ app()->getLocale() == 'en' ? 'Blog' : 'Blog' }}</a>
                        <a href="{{ route('pages.about') }}" class="hover:text-red-550 transition-colors uppercase">{{ app()->getLocale() == 'en' ? 'About' : 'À propos' }}</a>
                        <a href="{{ route('pages.contact') }}" class="hover:text-red-550 transition-colors uppercase">{{ app()->getLocale() == 'en' ? 'Contact' : 'Contact' }}</a>
                    </nav>
                    
                    <!-- Right: Icons & Locale -->
                    <div class="flex items-center gap-5">
                        <div class="flex items-center space-x-2 border-r pr-4 border-stone-200 dark:border-zinc-800">
                            <a href="{{ route('lang.switch', 'en') }}" class="text-[10px] font-black {{ app()->getLocale() == 'en' ? 'text-red-600' : 'text-stone-400' }}">EN</a>
                            <span class="text-stone-300 dark:text-zinc-700 text-xs">|</span>
                            <a href="{{ route('lang.switch', 'fr') }}" class="text-[10px] font-black {{ app()->getLocale() == 'fr' ? 'text-red-600' : 'text-stone-400' }}">FR</a>
                        </div>

                        <!-- Dark Mode Toggle -->
                        <button @click="$store.theme.toggle()" class="p-1.5 text-stone-500 dark:text-zinc-400 hover:bg-stone-100 dark:hover:bg-zinc-800 rounded-full transition focus:outline-none">
                            <span x-show="!$store.theme.darkMode">🌙</span>
                            <span x-show="$store.theme.darkMode">☀️</span>
                        </button>

                        <!-- Search Icon -->
                        <button @click="searchOpen = !searchOpen" class="text-stone-700 dark:text-stone-300 hover:text-red-600 transition-colors focus:outline-none" title="{{ __('Search') }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>

                        <!-- Account / Profile -->
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-stone-700 dark:text-stone-300 hover:text-red-600 transition-colors" title="{{ __('Dashboard') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-stone-700 dark:text-stone-300 hover:text-red-600 transition-colors" title="{{ __('Connexion') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            </a>
                        @endauth

                        <!-- Cart Icon -->
                        @auth
                            <a href="{{ route('cart.index') }}" class="text-stone-700 dark:text-stone-300 hover:text-red-600 transition-colors relative" title="{{ __('Panier') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 0a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                <span class="absolute -top-2.5 -right-2 bg-red-600 text-white font-heading font-black text-[9px] w-4.5 h-4.5 rounded-full flex items-center justify-center border-2 border-white dark:border-zinc-950">
                                    {{ auth()->user()->cartItems->count() }}
                                </span>
                            </a>
                        @else
                            <a href="javascript:void(0)" @click="showAuthModal = true" class="text-stone-700 dark:text-stone-300 hover:text-red-600 transition-colors relative" title="{{ __('Panier') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 0a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                <span class="absolute -top-2.5 -right-2 bg-red-600 text-white font-heading font-black text-[9px] w-4.5 h-4.5 rounded-full flex items-center justify-center border-2 border-white dark:border-zinc-950">0</span>
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Floating Search Input Panel -->
                <div x-show="searchOpen" 
                     x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="mt-4 pt-4 border-t border-stone-100 dark:border-zinc-800">
                    <form action="{{ route('home') }}" method="GET" class="flex gap-2">
                        <input type="text" name="search" x-model="searchQuery" placeholder="{{ app()->getLocale() == 'en' ? 'Search products (e.g. iPhone, Nike...)' : 'Rechercher des produits (ex: iPhone, Nike...)' }}" class="flex-grow text-xs border border-stone-250 dark:border-zinc-800 dark:bg-zinc-950 rounded-xl px-4 py-2.5 focus:border-red-500 focus:ring-red-500 outline-none">
                        <button type="submit" class="px-5 py-2.5 bg-red-600 hover:bg-red-750 text-white font-heading font-black text-[10px] tracking-widest uppercase rounded-xl transition">
                            {{ app()->getLocale() == 'en' ? 'Search' : 'Rechercher' }}
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Main Banner Slider (Alpine Functional Slider) -->
        <section class="mt-20 relative bg-zinc-900 text-white overflow-hidden min-h-[520px] flex items-center"
                 x-data="{ 
                     currentSlide: 0,
                     slides: [
                         {
                             tag: '{{ app()->getLocale() == 'en' ? 'Street Style' : 'Style Urbain' }}',
                             title: 'MUST HAVES',
                             desc: '{{ app()->getLocale() == 'en' ? 'Discover our new exclusive collection of streetwear and certified tech devices.' : 'Découvrez la nouvelle collection exclusive de vêtements de rue et de produits technologiques certifiés.' }}',
                             img: '{{ asset('images/striz/hero.jpg') }}',
                             btnText: '{{ app()->getLocale() == 'en' ? 'Shop Now' : 'Voir le shop' }}',
                             link: '{{ route('home') }}'
                         },
                         {
                             tag: '{{ app()->getLocale() == 'en' ? 'Technology' : 'Technologie' }}',
                             title: 'M1 ESSENTIALS',
                             desc: '{{ app()->getLocale() == 'en' ? 'Premium laptops and smartphones inspected and guaranteed by our SafeMarket escrow.' : 'Ordinateurs portables et smartphones haut de gamme inspectés et garantis avec notre escrow SafeMarket.' }}',
                             img: '{{ asset('images/products/macbook.jpg') }}',
                             btnText: '{{ app()->getLocale() == 'en' ? 'Explore Tech' : 'Découvrir la tech' }}',
                             link: '{{ route('home', ['category' => 'smartphones']) }}'
                         },
                         {
                             tag: '{{ app()->getLocale() == 'en' ? 'Urban Sneakers' : 'Baskets Urbaines' }}',
                             title: 'SNEAKER DROP',
                             desc: '{{ app()->getLocale() == 'en' ? 'The most sought-after original pairs in limited editions to complete your style.' : 'Les paires originales les plus recherchées du moment en édition limitée pour compléter votre style.' }}',
                             img: '{{ asset('images/products/nike.jpg') }}',
                             btnText: '{{ app()->getLocale() == 'en' ? 'Shop Sneakers' : 'Acheter des sneakers' }}',
                             link: '{{ route('home', ['category' => 'chaussures']) }}'
                         }
                     ],
                     next() { this.currentSlide = (this.currentSlide + 1) % this.slides.length },
                     prev() { this.currentSlide = (this.currentSlide - 1 + this.slides.length) % this.slides.length },
                     autoPlay() { setInterval(() => this.next(), 5500) }
                 }"
                 x-init="autoPlay()">
            
            <div class="absolute inset-0 opacity-15 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px] pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full py-16 grid grid-cols-1 md:grid-cols-2 items-center gap-10 relative z-10">
                <!-- Slide Text Content -->
                <div class="space-y-6">
                    <span class="text-red-500 font-heading font-black tracking-widest text-xs uppercase block" x-text="slides[currentSlide].tag"></span>
                    <h1 class="font-heading text-5xl sm:text-6xl lg:text-7xl font-extrabold uppercase leading-none tracking-tight">
                        <span x-text="slides[currentSlide].title.split(' ')[0]"></span><br/>
                        <span class="text-red-550 italic font-black" x-text="slides[currentSlide].title.split(' ').slice(1).join(' ')"></span>
                    </h1>
                    <p class="text-stone-300 text-sm max-w-md leading-relaxed" x-text="slides[currentSlide].desc"></p>
                    <div class="pt-4">
                        <a :href="slides[currentSlide].link" class="inline-flex items-center gap-2 px-8 py-3.5 bg-red-600 hover:bg-red-755 text-white font-heading font-black text-xs tracking-widest uppercase transition rounded shadow-lg shadow-red-900/30">
                            <span x-text="slides[currentSlide].btnText"></span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </div>
                
                <!-- Slide Image Container -->
                <div class="relative flex justify-center">
                    <div class="relative w-full max-w-md aspect-square rounded-[3rem] overflow-hidden border-4 border-zinc-800 shadow-2xl bg-zinc-950">
                        <img :src="slides[currentSlide].img" alt="{{ __('Streetwear Slide Image') }}" class="w-full h-full object-cover transition-all duration-500">
                    </div>
                    <!-- Absolute Decorative Badge -->
                    <div class="absolute -bottom-5 -left-5 bg-red-600 text-white font-heading font-black text-[10px] tracking-widest uppercase py-4 px-6 rounded-2xl rotate-6 shadow-xl">
                        {{ __('Est. 2026') }}
                    </div>
                </div>
            </div>
            
            <!-- Left and Right Slider Arrows (Functional) -->
            <button @click="prev()" class="absolute left-4 top-1/2 -translate-y-1/2 p-3 rounded-full bg-zinc-800/40 hover:bg-zinc-800 text-white transition focus:outline-none hidden md:block">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <button @click="next()" class="absolute right-4 top-1/2 -translate-y-1/2 p-3 rounded-full bg-zinc-800/40 hover:bg-zinc-800 text-white transition focus:outline-none hidden md:block">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </section>

        <!-- Streetwear Trending Collections & Grid Row 1 (Matches Striz style) -->
        <section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
                <!-- Left Banner Card (Vertical large, span 5 columns) -->
                <div class="lg:col-span-5 relative rounded-[2rem] overflow-hidden min-h-[400px] flex items-end p-8 shadow-xl bg-zinc-950 text-white group border border-stone-250/20">
                    <!-- Background image -->
                    <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 ease-out group-hover:scale-105 opacity-80" style="background-image: url('{{ asset('images/striz/left.jpg') }}')"></div>
                    <!-- Gradient overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                    
                    <div class="relative z-10 space-y-3">
                        <span class="text-red-500 font-heading font-black tracking-widest text-[10px] uppercase block">{{ app()->getLocale() == 'en' ? 'Summer Exclusive Collection' : 'Collection Exclusive d\'Été' }}</span>
                        <h2 class="font-heading text-3xl font-extrabold uppercase leading-tight">
                            {{ app()->getLocale() == 'en' ? 'STREET TRENDING' : 'TENDANCES URBAINES' }}<br/>
                            <span class="italic font-black text-red-550">2026</span>
                        </h2>
                        <div class="pt-2">
                            <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 border border-white px-5 py-2.5 rounded-lg font-heading font-black text-[10px] tracking-widest uppercase hover:bg-white hover:text-black transition duration-300">
                                <span>{{ app()->getLocale() == 'en' ? 'View Collection' : 'Voir la Collection' }}</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Two Product Cards (span 7 columns) -->
                <div class="lg:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-8">
                    @foreach($products->slice(0, 2) as $product)
                        <div class="group flex flex-col bg-white dark:bg-zinc-900 rounded-[2rem] border border-stone-200/60 dark:border-zinc-800/80 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300">
                            <!-- Image container -->
                            <div class="relative h-64 bg-stone-100 dark:bg-zinc-950 overflow-hidden flex items-center justify-center">
                                <img src="{{ $product->getImageUrl() }}" alt="{{ $product->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
                                
                                <!-- Stock Badge overlay -->
                                <div class="absolute top-4 left-4">
                                    <span class="px-2.5 py-1 text-[9px] font-black uppercase tracking-widest bg-white/90 dark:bg-zinc-900/90 text-stone-900 dark:text-white rounded shadow-sm">
                                        {{ $product->is_in_stock ? (app()->getLocale() == 'en' ? 'In Stock' : 'En Stock') : (app()->getLocale() == 'en' ? 'Out Of Stock' : 'Hors Stock') }}
                                    </span>
                                </div>

                                <!-- Hover quick view details -->
                                <div class="absolute inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    @auth
                                        <a href="{{ route('products.show', $product) }}" class="px-5 py-2.5 bg-red-600 hover:bg-red-750 text-white font-heading font-black text-[10px] tracking-widest uppercase rounded shadow transition">
                                            {{ app()->getLocale() == 'en' ? 'Quick View' : 'Aperçu Rapide' }}
                                        </a>
                                    @else
                                        <button @click="showAuthModal = true" class="px-5 py-2.5 bg-red-600 hover:bg-red-750 text-white font-heading font-black text-[10px] tracking-widest uppercase rounded shadow transition">
                                            {{ app()->getLocale() == 'en' ? 'Quick View' : 'Aperçu Rapide' }}
                                        </button>
                                    @endauth
                                </div>
                            </div>
                            
                            <!-- Card footer -->
                            <div class="p-6 flex-grow flex flex-col justify-between">
                                <div class="space-y-1">
                                    <span class="text-[10px] text-stone-400 dark:text-zinc-500 font-bold uppercase tracking-widest">{{ $product->category->name }}</span>
                                    <h3 class="font-heading font-extrabold text-base text-stone-950 dark:text-white uppercase leading-tight group-hover:text-red-600 transition-colors">
                                        @auth
                                            <a href="{{ route('products.show', $product) }}">{{ $product->title }}</a>
                                        @else
                                            <a href="javascript:void(0)" @click="showAuthModal = true">{{ $product->title }}</a>
                                        @endauth
                                    </h3>
                                </div>
                                <div class="flex justify-between items-center mt-4 pt-4 border-t border-stone-100 dark:border-zinc-800/80">
                                    <span class="text-lg font-black text-red-600 dark:text-red-500">{{ number_format($product->price, 0) }} FCFA</span>
                                    <div class="flex items-center text-xs text-amber-500">
                                        ★★★★★
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Streetwear Trending Collections & Grid Row 2 (Matches Striz style, reversed columns) -->
        <section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 border-t border-stone-200/50 dark:border-zinc-800/50">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
                <!-- Left Side: Two Product Cards (span 7 columns) -->
                <div class="lg:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-8">
                    @foreach($products->slice(2, 2) as $product)
                        <div class="group flex flex-col bg-white dark:bg-zinc-900 rounded-[2rem] border border-stone-200/60 dark:border-zinc-800/80 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300">
                            <!-- Image container -->
                            <div class="relative h-64 bg-stone-100 dark:bg-zinc-950 overflow-hidden flex items-center justify-center">
                                <img src="{{ $product->getImageUrl() }}" alt="{{ $product->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
                                
                                <!-- Stock Badge overlay -->
                                <div class="absolute top-4 left-4">
                                    <span class="px-2.5 py-1 text-[9px] font-black uppercase tracking-widest bg-white/90 dark:bg-zinc-900/90 text-stone-900 dark:text-white rounded shadow-sm">
                                        {{ $product->is_in_stock ? (app()->getLocale() == 'en' ? 'In Stock' : 'En Stock') : (app()->getLocale() == 'en' ? 'Out Of Stock' : 'Hors Stock') }}
                                    </span>
                                </div>

                                <!-- Hover quick view details -->
                                <div class="absolute inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    @auth
                                        <a href="{{ route('products.show', $product) }}" class="px-5 py-2.5 bg-red-600 hover:bg-red-755 text-white font-heading font-black text-[10px] tracking-widest uppercase rounded shadow transition">
                                            {{ app()->getLocale() == 'en' ? 'Quick View' : 'Aperçu Rapide' }}
                                        </a>
                                    @else
                                        <button @click="showAuthModal = true" class="px-5 py-2.5 bg-red-600 hover:bg-red-755 text-white font-heading font-black text-[10px] tracking-widest uppercase rounded shadow transition">
                                            {{ app()->getLocale() == 'en' ? 'Quick View' : 'Aperçu Rapide' }}
                                        </button>
                                    @endauth
                                </div>
                            </div>
                            
                            <!-- Card footer -->
                            <div class="p-6 flex-grow flex flex-col justify-between">
                                <div class="space-y-1">
                                    <span class="text-[10px] text-stone-400 dark:text-zinc-500 font-bold uppercase tracking-widest">{{ $product->category->name }}</span>
                                    <h3 class="font-heading font-extrabold text-base text-stone-950 dark:text-white uppercase leading-tight group-hover:text-red-600 transition-colors">
                                        @auth
                                            <a href="{{ route('products.show', $product) }}">{{ $product->title }}</a>
                                        @else
                                            <a href="javascript:void(0)" @click="showAuthModal = true">{{ $product->title }}</a>
                                        @endauth
                                    </h3>
                                </div>
                                <div class="flex justify-between items-center mt-4 pt-4 border-t border-stone-100 dark:border-zinc-800/80">
                                    <span class="text-lg font-black text-red-600 dark:text-red-500">{{ number_format($product->price, 0) }} FCFA</span>
                                    <div class="flex items-center text-xs text-amber-500">
                                        ★★★★★
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Right Banner Card (Vertical large, span 5 columns) -->
                <div class="lg:col-span-5 relative rounded-[2rem] overflow-hidden min-h-[400px] flex items-end p-8 shadow-xl bg-zinc-950 text-white group border border-stone-250/20">
                    <!-- Background image -->
                    <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 ease-out group-hover:scale-105 opacity-80" style="background-image: url('{{ asset('images/striz/right.jpg') }}')"></div>
                    <!-- Gradient overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                    
                    <div class="relative z-10 space-y-3">
                        <span class="text-red-500 font-heading font-black tracking-widest text-[10px] uppercase block">{{ app()->getLocale() == 'en' ? 'Spring Campaign' : 'Campagne de Printemps' }}</span>
                        <h2 class="font-heading text-3xl font-extrabold uppercase leading-tight">
                            {{ app()->getLocale() == 'en' ? 'THE ULTIMATE' : 'LE LOOK URBAIN' }}<br/>
                            <span class="italic font-black text-red-500">{{ app()->getLocale() == 'en' ? 'STREET LOOK' : 'PAR EXCELLENCE' }}</span>
                        </h2>
                        <div class="pt-2">
                            <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 border border-white px-5 py-2.5 rounded-lg font-heading font-black text-[10px] tracking-widest uppercase hover:bg-white hover:text-black transition duration-300">
                                <span>{{ app()->getLocale() == 'en' ? 'Shop Now' : 'Acheter Maintenant' }}</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Compact & Professional Footer -->
        @include('layouts.footer')

        <!-- Auth Required Modal Popup (Gate) -->
        <div x-show="showAuthModal" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4" 
             x-cloak>
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-gray-950/40 dark:bg-gray-950/70 backdrop-blur-sm transition-opacity" 
                 x-show="showAuthModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="showAuthModal = false"></div>

            <!-- Modal Content Card -->
            <div class="relative bg-white dark:bg-gray-900 rounded-[2rem] border border-stone-200/50 dark:border-zinc-800/80 p-8 shadow-2xl max-w-sm w-full z-10 transform transition-all"
                 x-show="showAuthModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <div class="text-center">
                    <!-- Icon -->
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-2xl bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400 mb-5 shadow-sm">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    
                    <!-- Title & Description -->
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                        {{ __('Connexion requise') }}
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed mb-6">
                        {{ __('Veuillez vous connecter ou créer un compte pour consulter les détails et négocier le prix de ce produit.') }}
                    </p>
                </div>
                
                <!-- Actions Buttons -->
                <div class="flex flex-col gap-2.5">
                    <a href="{{ route('login') }}" class="w-full justify-center inline-flex items-center px-4 py-3.5 rounded-xl bg-indigo-600 hover:bg-indigo-750 text-white font-bold text-sm shadow-lg shadow-indigo-100 dark:shadow-none transition">
                        {{ __('Se connecter') }}
                    </a>
                    <a href="{{ route('register') }}" class="w-full justify-center inline-flex items-center px-4 py-3.5 rounded-xl bg-gray-50 dark:bg-gray-800/80 hover:bg-gray-100 dark:hover:bg-zinc-700 text-gray-700 dark:text-gray-300 border border-gray-250 dark:border-zinc-700 font-bold text-sm transition">
                        {{ __('Créer un compte') }}
                    </a>
                    <button type="button" @click="showAuthModal = false" class="text-xs text-gray-400 dark:text-zinc-500 hover:text-zinc-400 mt-2 hover:underline">
                        {{ __('Continuer à explorer') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Blog Alert Toast -->
        <div x-show="blogToastOpen" 
             x-cloak
             x-transition
             class="fixed bottom-6 left-6 p-4 bg-zinc-900 border border-zinc-800 text-white text-xs rounded-2xl font-bold shadow-xl z-50 flex items-center gap-2.5">
            <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
            <span>{{ app()->getLocale() == 'en' ? 'The SafeMarket blog will be available very soon!' : 'Le blog SafeMarket sera disponible très prochainement !' }}</span>
        </div>

    </body>
</html>
