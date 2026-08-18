<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ma Boutique Vendeur') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50/50 dark:bg-gray-950/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- KYC status check banner -->
            @if(auth()->user()->kyc_status !== 'verified')
                <div class="mb-6 bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900/50 p-6 rounded-3xl text-amber-800 dark:text-amber-400 shadow-sm">
                    <div class="flex items-start gap-4">
                        <div class="p-3 bg-amber-500 text-white rounded-2xl shadow-md shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div>
                            @if(auth()->user()->kyc_status === 'pending')
                                <h3 class="text-base font-bold mb-1">{{ __('Validation de votre compte commerçant en cours') }}</h3>
                                <p class="text-sm text-amber-800/80 dark:text-amber-400/85 leading-relaxed">
                                    {{ __('Vos pièces d\'identité ont été transmises avec succès. Nos administrateurs examinent actuellement votre dossier KYC. Vous pouvez déjà ajouter et préparer vos produits ci-dessous.') }}
                                </p>
                            @else
                                <h3 class="text-base font-bold mb-1">{{ __('Vérification d\'identité KYC requise') }}</h3>
                                <p class="text-sm text-amber-800/80 dark:text-amber-400/85 leading-relaxed">
                                    {{ __('Veuillez soumettre vos pièces d\'identité dans votre profil. Vous pouvez néanmoins configurer votre boutique et vos produits ci-dessous.') }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Merchant Portal -->
            <div class="flex flex-col lg:flex-row gap-8" x-data="{ tab: 'products' }">
                    <!-- Sidebar -->
                    <div class="w-full lg:w-64 shrink-0">
                        <div class="bg-white dark:bg-gray-900 border border-gray-200/50 dark:border-gray-800/80 rounded-3xl p-5 shadow-sm space-y-1 sticky top-24">
                            <div class="px-3 py-1.5 mb-4 border-b border-gray-100 dark:border-gray-800/60">
                                <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('Boutique') }}</span>
                            </div>

                            <!-- Products -->
                            <button @click="tab = 'products'" 
                                    :class="tab === 'products' ? 'bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-850/50 hover:text-gray-900 dark:hover:text-gray-200'" 
                                    class="flex items-center gap-3 w-full px-3.5 py-3 rounded-xl text-sm font-semibold transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                <span>{{ __('Mes Produits') }}</span>
                            </button>

                            <!-- Orders -->
                            <button @click="tab = 'orders'" 
                                    :class="tab === 'orders' ? 'bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-850/50 hover:text-gray-900 dark:hover:text-gray-200'" 
                                    class="flex items-center gap-3 w-full px-3.5 py-3 rounded-xl text-sm font-semibold transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                <span>{{ __('Commandes Reçues') }}</span>
                            </button>

                            <!-- Stats -->
                            <button @click="tab = 'stats'" 
                                    :class="tab === 'stats' ? 'bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-850/50 hover:text-gray-900 dark:hover:text-gray-200'" 
                                    class="flex items-center gap-3 w-full px-3.5 py-3 rounded-xl text-sm font-semibold transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2"></path></svg>
                                <span>{{ __('Statistiques') }}</span>
                            </button>

                            <!-- Wallet -->
                            <button @click="tab = 'wallet'" 
                                    :class="tab === 'wallet' ? 'bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-850/50 hover:text-gray-900 dark:hover:text-gray-200'" 
                                    class="flex items-center gap-3 w-full px-3.5 py-3 rounded-xl text-sm font-semibold transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>{{ __('Wallet & Finances') }}</span>
                            </button>

                            <!-- Negotiations -->
                            <a href="{{ route('vendor.negotiations.index') }}" 
                               class="flex items-center gap-3 w-full px-3.5 py-3 rounded-xl text-sm font-semibold text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-850/50 hover:text-gray-900 dark:hover:text-gray-200 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                <span>{{ __('Négociations') }}</span>
                            </a>
                        </div>
                    </div>

                    <!-- Content Area -->
                    <div class="flex-grow min-w-0">
                        <!-- Products tab -->
                        <div x-show="tab === 'products'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                            <livewire:vendor.product-manager />
                        </div>

                        <!-- Orders tab -->
                        <div x-show="tab === 'orders'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                            <livewire:vendor.order-manager />
                        </div>

                        <!-- Stats tab -->
                        <div x-show="tab === 'stats'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                            <livewire:vendor.sales-stats />
                        </div>

                        <!-- Wallet tab -->
                        <div x-show="tab === 'wallet'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                            <livewire:vendor.wallet-manager />
                        </div>
                    </div>
                </div>
        </div>
    </div>
</x-app-layout>
