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
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? 'SafeMarket' }}</title>
        
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
        </style>
        @livewireStyles
    </head>
    <body class="antialiased bg-stone-50 text-stone-900 dark:bg-zinc-950 dark:text-zinc-100 transition-colors duration-300"
          @scroll.window="scrolled = (window.pageYOffset > 20)">

        <!-- Header (SafeMarket Premium Style) -->
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
                        <a href="{{ route('landing') }}" class="hover:text-red-500 transition-colors uppercase">{{ app()->getLocale() == 'en' ? 'Home' : 'Accueil' }}</a>
                        <a href="{{ route('home') }}" class="hover:text-red-500 transition-colors uppercase">{{ app()->getLocale() == 'en' ? 'Shop' : 'Marché' }}</a>
                        <a href="{{ route('products.spotlight') }}" class="hover:text-red-500 transition-colors uppercase">{{ app()->getLocale() == 'en' ? 'Product' : 'Produit' }}</a>
                        <a href="{{ route('pages.on-sale') }}" class="hover:text-red-500 transition-colors uppercase">{{ app()->getLocale() == 'en' ? 'On Sale' : 'En Promo' }}</a>
                        <a href="{{ route('pages.blog') }}" class="hover:text-red-500 transition-colors uppercase">{{ app()->getLocale() == 'en' ? 'Blog' : 'Blog' }}</a>
                        <a href="{{ route('pages.about') }}" class="hover:text-red-500 transition-colors uppercase">{{ app()->getLocale() == 'en' ? 'About' : 'À propos' }}</a>
                        <a href="{{ route('pages.contact') }}" class="hover:text-red-500 transition-colors uppercase">{{ app()->getLocale() == 'en' ? 'Contact' : 'Contact' }}</a>
                    </nav>
                    
                    <!-- Right: Icons & Locale -->
                    <div class="flex items-center gap-5">
                        <div class="flex items-center space-x-2 border-r pr-4 border-stone-200 dark:border-zinc-800">
                            <a href="{{ route('lang.switch', 'en') }}" class="text-[10px] font-black {{ app()->getLocale() == 'en' ? 'text-red-600' : 'text-stone-400' }}">EN</a>
                            <span class="text-stone-300 dark:text-zinc-700 text-xs">|</span>
                            <a href="{{ route('lang.switch', 'fr') }}" class="text-[10px] font-black {{ app()->getLocale() == 'fr' ? 'text-red-600' : 'text-stone-400' }}">FR</a>
                        </div>

                        <!-- Dark Mode Toggle SVG -->
                        <button @click="$store.theme.toggle()" class="p-2 text-stone-500 dark:text-zinc-400 hover:bg-stone-100 dark:hover:bg-zinc-800 rounded-full transition focus:outline-none" title="{{ __('Toggle Theme') }}">
                            <template x-if="!$store.theme.darkMode">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                            </template>
                            <template x-if="$store.theme.darkMode">
                                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 18v1m9-11h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.344l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </template>
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

        <!-- Page Content -->
        <main class="pt-24 min-h-[calc(100vh-80px)]">
            {{ $slot }}
        </main>

        @include('layouts.footer')

        <!-- Auth Required Modal Popup (Gate) -->
        <div x-show="showAuthModal" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 min-h-screen" 
             x-cloak>
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-gray-950/50 backdrop-blur-sm transition-opacity" 
                 x-show="showAuthModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="showAuthModal = false"></div>

            <!-- Modal Content Card -->
            <div class="relative bg-white dark:bg-gray-900 rounded-[2rem] border border-stone-200/50 dark:border-zinc-800/80 p-8 shadow-2xl max-w-sm w-full z-10 transform transition-all text-center"
                 x-show="showAuthModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <!-- Icon -->
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-2xl bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 mb-5 shadow-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                
                <!-- Title & Description -->
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                    {{ app()->getLocale() == 'en' ? 'Login Required' : 'Connexion requise' }}
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed mb-6">
                    {{ app()->getLocale() == 'en' ? 'Please log in or create an account to view details and negotiate the price of this product.' : 'Veuillez vous connecter ou créer un compte pour consulter les détails et négocier le prix de ce produit.' }}
                </p>
                
                <!-- Actions Buttons -->
                <div class="flex flex-col gap-2.5">
                    <a href="{{ route('login') }}" class="w-full justify-center inline-flex items-center px-4 py-3.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-bold text-sm shadow-lg transition">
                        {{ app()->getLocale() == 'en' ? 'Login' : 'Se connecter' }}
                    </a>
                    <a href="{{ route('register') }}" class="w-full justify-center inline-flex items-center px-4 py-3.5 rounded-xl bg-gray-50 dark:bg-gray-800/80 hover:bg-gray-100 dark:hover:bg-zinc-700 text-gray-700 dark:text-gray-300 border border-gray-250 dark:border-zinc-700 font-bold text-sm transition">
                        {{ app()->getLocale() == 'en' ? 'Create an account' : 'Créer un compte' }}
                    </a>
                    <button type="button" @click="showAuthModal = false" class="text-xs text-gray-400 dark:text-zinc-500 hover:text-zinc-400 mt-2 hover:underline">
                        {{ app()->getLocale() == 'en' ? 'Continue exploring' : 'Continuer à explorer' }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Blog Alert Toast -->
        <div x-show="blogToastOpen" 
             x-cloak
             x-transition
             class="fixed bottom-6 left-6 p-4 bg-zinc-900 border border-zinc-800 text-white text-xs rounded-xl font-bold shadow-xl z-50 flex items-center gap-2">
            <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
            <span>{{ __('Le blog SafeMarket sera disponible très prochainement !') }}</span>
        </div>

        @livewireScripts
    </body>
</html>
