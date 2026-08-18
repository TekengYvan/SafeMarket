<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 text-center">
                    <div class="mb-6">
                        <x-application-logo class="w-32 h-32 mx-auto mb-4" />
                        <h3 class="text-2xl font-bold italic">{{ __('Bienvenue sur Safemarket') }}, {{ Auth::user()->name }} !</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                        <a href="{{ route('home') }}" class="p-6 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl hover:bg-indigo-100 dark:hover:bg-indigo-900/40 transition border border-transparent dark:border-gray-700">
                            <div class="mb-3 flex justify-center"><svg class="w-10 h-10 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 11H4L5 9z"/></svg></div>
                            <h4 class="font-bold text-indigo-600 dark:text-indigo-400">{{ __('Marketplace') }}</h4>
                            <p class="text-xs opacity-75">{{ __('Browse and buy products securely.') }}</p>
                        </a>
                        <a href="{{ route('vendor.products.index') }}" class="p-6 bg-green-50 dark:bg-green-900/20 rounded-xl hover:bg-green-100 dark:hover:bg-green-900/40 transition border border-transparent dark:border-gray-700">
                            <div class="mb-3 flex justify-center"><svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg></div>
                            <h4 class="font-bold text-green-600 dark:text-green-400">{{ __('Ma Boutique') }}</h4>
                            <p class="text-xs opacity-75">{{ __('Manage your listings and sales.') }}</p>
                        </a>
                        <a href="{{ route('orders.index') }}" class="p-6 bg-blue-50 dark:bg-blue-900/20 rounded-xl hover:bg-blue-100 dark:hover:bg-blue-900/40 transition border border-transparent dark:border-gray-700">
                            <div class="mb-3 flex justify-center"><svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div>
                            <h4 class="font-bold text-blue-600 dark:text-blue-400">{{ __('Mes Commandes') }}</h4>
                            <p class="text-xs opacity-75">{{ __('Track your orders and ESCROW.') }}</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
