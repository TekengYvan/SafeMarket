<x-guest-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-white dark:bg-gray-900 w-full">
        <!-- Left Side: Fancy Image -->
        <div class="hidden md:flex md:w-1/2 bg-indigo-600 items-center justify-center relative overflow-hidden">
            <img src="https://images.unsplash.com/photo-1557821552-17105176677c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" class="absolute inset-0 w-full h-full object-cover opacity-50">
            <div class="relative z-10 text-center px-12">
                <h1 class="text-5xl font-extrabold text-white mb-6">{{ __('Bienvenue sur Safemarket') }}</h1>
                <p class="text-xl text-indigo-100">{{ __('La marketplace la plus sécurisée pour vos achats et ventes avec système ESCROW.') }}</p>
                <div class="mt-12 flex justify-center gap-8 text-white">
                    <div class="text-center">
                        <p class="text-3xl font-bold">100%</p>
                        <p class="text-sm uppercase tracking-widest opacity-75">{{ __('Sécurisé') }}</p>
                    </div>
                    <div class="text-center border-l border-r border-indigo-400 px-8">
                        <p class="text-3xl font-bold">3%</p>
                        <p class="text-sm uppercase tracking-widest opacity-75">{{ __('Frais Fixes') }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl font-bold">24/7</p>
                        <p class="text-sm uppercase tracking-widest opacity-75">{{ __('Support') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="w-full md:w-1/2 flex items-center justify-center p-8 bg-white dark:bg-gray-900">
            <div class="w-full max-w-md">
                <div class="flex justify-center mb-8">
                    <a href="/">
                        <x-application-logo class="w-24 h-24" />
                    </a>
                </div>

                <h2 class="text-2xl font-bold text-gray-800 dark:text-white text-center mb-8 italic">{{ __('Connectez-vous à votre compte') }}</h2>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Mot de passe')" />
                        <x-text-input id="password" class="block mt-1 w-full"
                                        type="password"
                                        name="password"
                                        required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="block mt-4 flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                            <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Se souvenir de moi') }}</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                                {{ __('Mot de passe oublié ?') }}
                            </a>
                        @endif
                    </div>

                    <div class="mt-8">
                        <x-primary-button class="w-full justify-center py-3 text-lg">
                            {{ __('Se connecter') }}
                        </x-primary-button>
                    </div>

                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Pas encore de compte ?') }} 
                            <a href="{{ route('register') }}" class="font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 underline">{{ __('Inscrivez-vous ici') }}</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
