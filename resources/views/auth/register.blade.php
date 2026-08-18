<x-guest-layout>
    <div class="flex flex-col md:flex-row min-h-screen bg-white dark:bg-gray-900 w-full">
        <!-- Left Side: Fancy Image -->
        <div class="hidden md:flex md:w-1/2 bg-indigo-600 items-center justify-center relative overflow-hidden">
            <img src="https://images.unsplash.com/photo-1472851294608-062f824d29cc?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" class="absolute inset-0 w-full h-full object-cover opacity-50">
            <div class="relative z-10 text-center px-12">
                <h1 class="text-5xl font-extrabold text-white mb-6">{{ __('Rejoignez Safemarket') }}</h1>
                <p class="text-xl text-indigo-100">{{ __('Devenez acheteur ou vendeur sur la plateforme de commerce la plus fiable.') }}</p>
                <ul class="mt-8 text-white text-left max-w-xs mx-auto space-y-4">
                    <li class="flex items-center gap-3">
                        <div class="bg-indigo-500 rounded-full p-1"><svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg></div>
                        <span>{{ __('Paiement ESCROW sécurisé') }}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="bg-indigo-500 rounded-full p-1"><svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg></div>
                        <span>{{ __("Vérification d'identité (KYC)") }}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="bg-indigo-500 rounded-full p-1"><svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg></div>
                        <span>{{ __('Support client réactif') }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Right Side: Register Form -->
        <div class="w-full md:w-1/2 flex items-center justify-center p-8 bg-white dark:bg-gray-900 overflow-y-auto">
            <div class="w-full max-w-md">
                <div class="flex justify-center mb-8">
                    <a href="/">
                        <x-application-logo class="w-24 h-24" />
                    </a>
                </div>

                <h2 class="text-2xl font-bold text-gray-800 dark:text-white text-center mb-6">{{ __('Créez votre compte gratuit') }}</h2>

                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" x-data="{ role: 'buyer' }">
                    @csrf

                    <!-- Name -->
                    <div>
                        <x-input-label for="name" :value="__('Nom complet')" />
                        <x-text-input id="name" class="block mt-1 w-full text-sm" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email Address -->
                    <div class="mt-4">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full text-sm" type="email" name="email" :value="old('email')" required autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Phone Number -->
                    <div class="mt-4">
                        <x-input-label for="phone_number" :value="__('Numéro de téléphone')" />
                        <x-text-input id="phone_number" class="block mt-1 w-full text-sm" type="text" name="phone_number" :value="old('phone_number')" required />
                        <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Mot de passe')" />
                        <x-text-input id="password" class="block mt-1 w-full text-sm"
                                        type="password"
                                        name="password"
                                        required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="mt-4">
                        <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" />
                        <x-text-input id="password_confirmation" class="block mt-1 w-full text-sm"
                                        type="password"
                                        name="password_confirmation" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <!-- Role Selection -->
                    <div class="mt-4">
                        <x-input-label :value="__('Type de compte')" />
                        <div class="grid grid-cols-2 gap-4 mt-2">
                            <label class="flex items-center gap-2.5 p-3 rounded-xl border border-gray-250 dark:border-gray-800 dark:bg-gray-950 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-900 transition" :class="role === 'buyer' ? 'border-indigo-500 ring-2 ring-indigo-500/20' : ''">
                                <input type="radio" name="role" value="buyer" x-model="role" class="text-indigo-650 focus:ring-indigo-500">
                                <div>
                                    <p class="text-xs font-bold text-gray-850 dark:text-white">{{ __('Acheteur') }}</p>
                                    <p class="text-[9px] text-gray-400">{{ __('Pour acheter des produits') }}</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-2.5 p-3 rounded-xl border border-gray-250 dark:border-gray-800 dark:bg-gray-950 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-900 transition" :class="role === 'vendor' ? 'border-indigo-500 ring-2 ring-indigo-500/20' : ''">
                                <input type="radio" name="role" value="vendor" x-model="role" class="text-indigo-650 focus:ring-indigo-500">
                                <div>
                                    <p class="text-xs font-bold text-gray-850 dark:text-white">{{ __('Commerçant') }}</p>
                                    <p class="text-[9px] text-gray-400">{{ __('Pour vendre (KYC requis)') }}</p>
                                </div>
                            </label>
                        </div>
                        <x-input-error :messages="$errors->get('role')" class="mt-2" />
                    </div>

                    <!-- KYC Document Upload (shown only for merchants) -->
                    <div class="mt-4 border-t border-gray-100 dark:border-gray-800/80 pt-4" x-show="role === 'vendor'" x-transition>
                        <x-input-label for="id_card" :value="__('Pièce d\'identité (Recto/Verso ou Passeport)')" />
                        <div class="mt-1.5 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-800 border-dashed rounded-2xl bg-gray-50/50 dark:bg-gray-950">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 dark:text-gray-400 justify-center">
                                    <label for="id_card" class="relative cursor-pointer bg-transparent rounded-md font-bold text-indigo-650 hover:text-indigo-500 focus-within:outline-none">
                                        <span>{{ __('Téléverser un fichier') }}</span>
                                        <input id="id_card" name="id_card" type="file" class="sr-only">
                                    </label>
                                </div>
                                <p class="text-[10px] text-gray-400">{{ __('PNG, JPG, GIF jusqu\'à 4 Mo') }}</p>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('id_card')" class="mt-2" />
                    </div>

                    <div class="mt-8">
                        <x-primary-button class="w-full justify-center py-3.5 text-base bg-indigo-600 rounded-xl shadow-lg shadow-indigo-100 dark:shadow-none hover:bg-indigo-700 transition">
                            {{ __('Créer mon compte') }}
                        </x-primary-button>
                    </div>

                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Déjà inscrit ?') }} 
                            <a href="{{ route('login') }}" class="font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 underline">{{ __('Connectez-vous ici') }}</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
