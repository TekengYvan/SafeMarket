<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Administration Safemarket') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50/50 dark:bg-gray-950/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8" x-data="{ tab: 'analytics' }">
                <!-- Sidebar -->
                <div class="w-full lg:w-64 shrink-0">
                    <div class="bg-white dark:bg-gray-900 border border-gray-200/50 dark:border-gray-800/80 rounded-3xl p-5 shadow-sm space-y-1 sticky top-24">
                        <div class="px-3 py-1.5 mb-4 border-b border-gray-100 dark:border-gray-800/60">
                            <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('Menu Admin') }}</span>
                        </div>

                        <!-- Analytics -->
                        <button @click="tab = 'analytics'" 
                                :class="tab === 'analytics' ? 'bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-850/50 hover:text-gray-900 dark:hover:text-gray-200'" 
                                class="flex items-center gap-3 w-full px-3.5 py-3 rounded-xl text-sm font-semibold transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2"></path></svg>
                            <span>{{ __('Analytique') }}</span>
                        </button>

                        <!-- Users -->
                        <button @click="tab = 'users'" 
                                :class="tab === 'users' ? 'bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-850/50 hover:text-gray-900 dark:hover:text-gray-200'" 
                                class="flex items-center gap-3 w-full px-3.5 py-3 rounded-xl text-sm font-semibold transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <span>{{ __('Utilisateurs') }}</span>
                        </button>

                        <!-- KYC -->
                        <button @click="tab = 'kyc'" 
                                :class="tab === 'kyc' ? 'bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-850/50 hover:text-gray-900 dark:hover:text-gray-200'" 
                                class="flex items-center gap-3 w-full px-3.5 py-3 rounded-xl text-sm font-semibold transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            <span>{{ __('Vérifications KYC') }}</span>
                        </button>

                        <!-- Categories -->
                        <button @click="tab = 'categories'" 
                                :class="tab === 'categories' ? 'bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-850/50 hover:text-gray-900 dark:hover:text-gray-200'" 
                                class="flex items-center gap-3 w-full px-3.5 py-3 rounded-xl text-sm font-semibold transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                            <span>{{ __('Catégories') }}</span>
                        </button>

                        <!-- Disputes -->
                        <button @click="tab = 'disputes'" 
                                :class="tab === 'disputes' ? 'bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-850/50 hover:text-gray-900 dark:hover:text-gray-200'" 
                                class="flex items-center gap-3 w-full px-3.5 py-3 rounded-xl text-sm font-semibold transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <span>{{ __('Gestion Litiges') }}</span>
                        </button>
                    </div>
                </div>

                <!-- Content Area -->
                <div class="flex-grow min-w-0">
                    <div x-show="tab === 'analytics'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                        <livewire:admin.global-analytics />
                    </div>

                    <div x-show="tab === 'users'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                        <livewire:admin.user-management />
                    </div>

                    <div x-show="tab === 'kyc'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                        <livewire:admin.kyc-verification />
                    </div>

                    <div x-show="tab === 'categories'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                        <livewire:admin.category-manager />
                    </div>

                    <div x-show="tab === 'disputes'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                        <livewire:admin.dispute-manager />
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
