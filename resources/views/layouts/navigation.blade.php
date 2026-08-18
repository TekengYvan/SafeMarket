<nav x-data="{ open: false, showLogoutModal: false, searchOpen: false, searchQuery: '' }" class="sticky top-0 z-50 bg-white/80 dark:bg-gray-900/85 backdrop-blur-md border-b border-gray-200/50 dark:border-gray-800/50 shadow-sm transition-all duration-300">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('landing') }}" class="hover:scale-105 transition-transform duration-200 flex items-center gap-1.5">
                        <span class="font-bold text-2xl tracking-tight text-gray-900 dark:text-white italic">
                            SafeMarket<span class="text-red-500 font-sans font-light">.</span>
                        </span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')" class="inline-flex items-center gap-1.5 text-sm font-medium tracking-wide">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        <span>{{ __('Marketplace') }}</span>
                    </x-nav-link>

                    @auth
                        <x-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.*')" class="inline-flex items-center gap-1.5 text-sm font-medium tracking-wide">
                            <div class="relative flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                <span>{{ __('Panier') }}</span>
                                @php $navCartCount = auth()->user()->cartItems()->count(); @endphp
                                @if($navCartCount > 0)
                                    <span class="inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-white bg-red-600 dark:bg-red-500 rounded-full min-w-[18px] text-center ml-1 animate-pulse">{{ $navCartCount }}</span>
                                @endif
                            </div>
                        </x-nav-link>

                        <x-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')" class="inline-flex items-center gap-1.5 text-sm font-medium tracking-wide">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            <span>{{ __('Mes Commandes') }}</span>
                        </x-nav-link>

                        <x-nav-link :href="route('negotiations.index')" :active="request()->routeIs('negotiations.*')" class="inline-flex items-center gap-1.5 text-sm font-medium tracking-wide">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            <span>{{ __('Négociations') }}</span>
                        </x-nav-link>

                        <x-nav-link :href="route('vendor.dashboard')" :active="request()->routeIs('vendor.*')" class="inline-flex items-center gap-1.5 text-sm font-medium tracking-wide">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            <span>{{ __('Ma Boutique') }}</span>
                        </x-nav-link>

                        @if(Auth::user()->is_admin)
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')" class="inline-flex items-center gap-1.5 text-sm font-medium tracking-wide text-rose-500 hover:text-rose-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                <span>{{ __('Administration') }}</span>
                            </x-nav-link>
                        @endif
                    @else
                        <x-nav-link :href="route('login')" class="text-sm font-medium">
                            {{ __('Connexion') }}
                        </x-nav-link>
                        <x-nav-link :href="route('register')" class="text-sm font-medium">
                            {{ __('Inscription') }}
                        </x-nav-link>
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                <!-- Language Switcher -->
                <div class="flex items-center space-x-2 border-r pr-4 border-gray-200 dark:border-gray-700">
                    <a href="{{ route('lang.switch', 'en') }}" class="text-xs font-bold {{ app()->getLocale() == 'en' ? 'text-red-600' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">EN</a>
                    <span class="text-gray-300 dark:text-gray-600">|</span>
                    <a href="{{ route('lang.switch', 'fr') }}" class="text-xs font-bold {{ app()->getLocale() == 'fr' ? 'text-red-600' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">FR</a>
                </div>

                <!-- Dark Mode Toggle -->
                <button type="button" 
                        onclick="window.toggleTheme()" 
                        class="p-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full transition focus:outline-none" 
                        title="{{ __('Basculer le mode sombre/clair') }}">
                    <svg class="w-5 h-5 hidden dark:block text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 18v1m9-11h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.344l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <svg class="w-5 h-5 block dark:hidden text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                </button>

                <!-- Search Toggle Button -->
                <button @click="searchOpen = !searchOpen" class="p-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full transition focus:outline-none" title="{{ __('Search') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>

                @auth
                    <livewire:notification-bell />

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-2 px-3 py-1.5 border border-gray-200/60 dark:border-gray-800 text-sm font-semibold rounded-2xl text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-850 focus:outline-none transition shadow-sm">
                                <img src="{{ Auth::user()->getAvatarUrl() }}" alt="{{ Auth::user()->name }}" class="w-7 h-7 rounded-full object-cover border border-red-500 shrink-0 shadow-sm">
                                <div class="text-xs font-bold">{{ Auth::user()->name }} <span class="text-emerald-600 dark:text-emerald-400 font-extrabold ml-1">({{ number_format(Auth::user()->balance, 0, ',', ' ') }} FCFA)</span></div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <x-dropdown-link :href="route('logout')"
                                    @click.prevent="showLogoutModal = true">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
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
         class="border-b border-gray-250/40 dark:border-gray-800/40 bg-white/95 dark:bg-gray-900/95 py-4 px-4 sm:px-6 lg:px-8">
        <form action="{{ route('home') }}" method="GET" class="max-w-7xl mx-auto flex gap-2">
            <input type="text" name="search" x-model="searchQuery" placeholder="{{ __('Rechercher des produits (ex: iPhone, Nike...)') }}" class="flex-grow text-xs border border-gray-205 dark:border-gray-800 dark:bg-zinc-950 rounded-xl px-4 py-2.5 focus:border-red-500 focus:ring-red-500 outline-none dark:text-white">
            <button type="submit" class="px-5 py-2.5 bg-red-600 hover:bg-red-750 text-white font-bold text-xs uppercase rounded-xl transition shadow">
                {{ __('Rechercher') }}
            </button>
        </form>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                {{ __('Marketplace') }}
            </x-responsive-nav-link>
            @auth
                <x-responsive-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.*')">
                    {{ __('Panier') }} ({{ auth()->user()->cartItems->count() }})
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">
                    {{ __('Mes Commandes') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('negotiations.index')" :active="request()->routeIs('negotiations.*')">
                    {{ __('Négociations') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('vendor.dashboard')" :active="request()->routeIs('vendor.*')">
                    {{ __('Ma Boutique') }}
                </x-responsive-nav-link>
                @if(Auth::user()->is_admin)
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                        {{ __('Administration') }}
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <!-- Responsive Language & Theme Switcher -->
        <div class="pt-4 pb-3 border-t border-gray-200 dark:border-gray-800">
            <div class="flex items-center justify-around px-4">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('lang.switch', 'en') }}" class="text-sm font-bold {{ app()->getLocale() == 'en' ? 'text-red-600' : 'text-gray-500' }}">EN</a>
                    <a href="{{ route('lang.switch', 'fr') }}" class="text-sm font-bold {{ app()->getLocale() == 'fr' ? 'text-red-600' : 'text-gray-500' }}">FR</a>
                </div>
                <button type="button" onclick="window.toggleTheme()" class="p-2 text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 focus:outline-none flex items-center gap-1.5 font-bold text-xs">
                    <span class="flex items-center gap-1.5 dark:hidden">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        <span>{{ __('Mode Sombre') }}</span>
                    </span>
                    <span class="hidden dark:flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 18v1m9-11h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.344l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span>{{ __('Mode Clair') }}</span>
                    </span>
                </button>
            </div>
        </div>

        @auth
            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-3 border-t border-gray-200 dark:border-gray-800">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ number_format(Auth::user()->balance, 2) }} FCFA</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <x-responsive-nav-link :href="route('logout')"
                            @click.prevent="showLogoutModal = true">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </div>
            </div>
        @else
            <div class="pt-4 pb-3 border-t border-gray-200 dark:border-gray-800">
                <x-responsive-nav-link :href="route('login')">
                    {{ __('Connexion') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('register')">
                    {{ __('Inscription') }}
                </x-responsive-nav-link>
            </div>
        @endauth
    </div>

    <!-- Logout Confirmation Modal -->
    <div x-show="showLogoutModal" 
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 w-screen h-screen overflow-y-auto" 
         x-cloak>
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-950/60 backdrop-blur-sm transition-opacity" 
             x-show="showLogoutModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="showLogoutModal = false"></div>

        <!-- Modal Content Card -->
        <div class="relative bg-white dark:bg-gray-900 rounded-[2rem] border border-gray-200/50 dark:border-gray-800/80 p-6 shadow-2xl max-w-sm w-full z-10 transform transition-all my-auto"
             x-show="showLogoutModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            
            <div class="text-center">
                <!-- Icon -->
                <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-2xl bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-450 mb-4 shadow-sm">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </div>
                
                <!-- Title & Description -->
                <h3 class="text-base font-bold text-gray-900 dark:text-white mb-2">
                    {{ __('Confirmation de déconnexion') }}
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed mb-6">
                    {{ __('Voulez-vous vraiment vous déconnecter de votre session SafeMarket ?') }}
                </p>
            </div>
            
            <!-- Actions Buttons -->
            <div class="flex flex-col gap-2">
                <button type="button" 
                        @click="document.getElementById('logout-form').submit()"
                        class="w-full justify-center inline-flex items-center px-4 py-3 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm shadow-lg shadow-rose-100 dark:shadow-none transition">
                    {{ __('Oui, se déconnecter') }}
                </button>
                <button type="button" 
                        @click="showLogoutModal = false"
                        class="w-full justify-center inline-flex items-center px-4 py-3 rounded-xl bg-gray-50 dark:bg-gray-800/80 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-250 dark:border-gray-700 font-bold text-sm transition">
                    {{ __('Annuler') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Hidden Logout Form -->
    <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
        @csrf
    </form>
</nav>
