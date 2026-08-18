{{-- Simple, clean footer — light & dark mode --}}
<footer id="contact-footer" class="bg-gray-50 dark:bg-gray-950 border-t border-gray-200 dark:border-gray-800/60 text-gray-500 dark:text-gray-400 text-xs transition-colors duration-200">

    {{-- Main footer body --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- Top: Brand + Contact --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1 font-black text-xl italic tracking-tight text-gray-900 dark:text-white">
                    SafeMarket<span class="text-red-500 not-italic font-sans">.</span>
                </a>
                <p class="mt-1 text-gray-400 dark:text-gray-500 text-[11px] max-w-xs leading-relaxed">
                    {{ app()->getLocale() == 'en' ? 'Secure marketplace for Cameroon & Central Africa.' : 'Marketplace sécurisée pour le Cameroun et l\'Afrique Centrale.' }}
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="mailto:support@safemarket.cm"
                   class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg
                          bg-white dark:bg-gray-900
                          border border-gray-200 dark:border-gray-800
                          text-gray-600 dark:text-gray-400
                          hover:border-red-400 dark:hover:border-red-500/40
                          hover:text-gray-900 dark:hover:text-white
                          transition-colors text-[11px]">
                    <svg class="w-3.5 h-3.5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    support@safemarket.cm
                </a>
                <a href="tel:+237688889514"
                   class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg
                          bg-white dark:bg-gray-900
                          border border-gray-200 dark:border-gray-800
                          text-gray-600 dark:text-gray-400
                          hover:border-red-400 dark:hover:border-red-500/40
                          hover:text-gray-900 dark:hover:text-white
                          transition-colors text-[11px]">
                    <svg class="w-3.5 h-3.5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    +237 6 88 88 95 14
                </a>
            </div>
        </div>

        {{-- Links grid --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6 pb-8 border-b border-gray-200 dark:border-gray-800/60">

            {{-- Company --}}
            <div>
                <p class="font-bold text-[10px] uppercase tracking-widest text-gray-800 dark:text-white mb-3">
                    {{ app()->getLocale() == 'en' ? 'Company' : 'Entreprise' }}
                </p>
                <ul class="space-y-1.5">
                    <li><a href="{{ route('home') }}" class="hover:text-gray-900 dark:hover:text-white transition-colors">{{ app()->getLocale() == 'en' ? 'Home' : 'Accueil' }}</a></li>
                    <li><a href="{{ route('pages.about') }}" class="hover:text-gray-900 dark:hover:text-white transition-colors">{{ app()->getLocale() == 'en' ? 'About Us' : 'À propos' }}</a></li>
                    <li><a href="{{ route('landing') }}#features" class="hover:text-gray-900 dark:hover:text-white transition-colors">{{ app()->getLocale() == 'en' ? 'Features' : 'Fonctionnalités' }}</a></li>
                    <li><a href="{{ route('pages.blog') }}" class="hover:text-gray-900 dark:hover:text-white transition-colors">Blog</a></li>
                    <li><a href="{{ route('vendor.dashboard') }}" class="hover:text-gray-900 dark:hover:text-white transition-colors">{{ app()->getLocale() == 'en' ? 'My SafePack' : 'Mon SafePack' }}</a></li>
                </ul>
            </div>

            {{-- Buy --}}
            <div>
                <p class="font-bold text-[10px] uppercase tracking-widest text-gray-800 dark:text-white mb-3">
                    {{ app()->getLocale() == 'en' ? 'Buy' : 'Acheter' }}
                </p>
                <ul class="space-y-1.5">
                    <li>
                        <a href="{{ route('home') }}" class="hover:text-gray-900 dark:hover:text-white transition-colors flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span>
                            {{ app()->getLocale() == 'en' ? 'Buyer Protection' : 'Protection acheteur' }}
                        </a>
                    </li>
                    <li><a href="{{ route('home') }}" class="hover:text-gray-900 dark:hover:text-white transition-colors">{{ app()->getLocale() == 'en' ? 'Purchase Guide' : 'Guide d\'achat' }}</a></li>
                    <li><a href="{{ route('home') }}" class="hover:text-gray-900 dark:hover:text-white transition-colors">{{ app()->getLocale() == 'en' ? 'Returns Policy' : 'Politique de retour' }}</a></li>
                    <li><a href="{{ route('home') }}" class="hover:text-gray-900 dark:hover:text-white transition-colors">{{ app()->getLocale() == 'en' ? 'Delivery Info' : 'Informations livraison' }}</a></li>
                    <li><a href="{{ route('home') }}" class="hover:text-gray-900 dark:hover:text-white transition-colors">{{ app()->getLocale() == 'en' ? 'Payment Methods' : 'Moyens de paiement' }}</a></li>
                </ul>
            </div>

            {{-- Sell --}}
            <div>
                <p class="font-bold text-[10px] uppercase tracking-widest text-gray-800 dark:text-white mb-3">
                    {{ app()->getLocale() == 'en' ? 'Sell' : 'Vendre' }}
                </p>
                <ul class="space-y-1.5">
                    <li><a href="{{ route('vendor.dashboard') }}" class="hover:text-gray-900 dark:hover:text-white transition-colors">{{ app()->getLocale() == 'en' ? 'Seller Guide' : 'Guide du vendeur' }}</a></li>
                    <li><a href="{{ route('vendor.dashboard') }}" class="hover:text-gray-900 dark:hover:text-white transition-colors">{{ app()->getLocale() == 'en' ? 'Seller Fees (0%)' : 'Frais vendeur (0%)' }}</a></li>
                    <li><a href="{{ route('vendor.dashboard') }}" class="hover:text-gray-900 dark:hover:text-white transition-colors">{{ app()->getLocale() == 'en' ? 'Seller Protection' : 'Protection vendeur' }}</a></li>
                    <li><a href="{{ route('vendor.dashboard') }}" class="hover:text-gray-900 dark:hover:text-white transition-colors">{{ app()->getLocale() == 'en' ? 'Affiliate Program' : 'Programme d\'affiliation' }}</a></li>
                </ul>
            </div>

            {{-- Legal --}}
            <div>
                <p class="font-bold text-[10px] uppercase tracking-widest text-gray-800 dark:text-white mb-3">
                    {{ app()->getLocale() == 'en' ? 'Legal' : 'Légal' }}
                </p>
                <ul class="space-y-1.5">
                    <li><a href="{{ route('pages.about') }}" class="hover:text-gray-900 dark:hover:text-white transition-colors">{{ app()->getLocale() == 'en' ? 'Privacy Policy' : 'Confidentialité' }}</a></li>
                    <li><a href="{{ route('pages.about') }}" class="hover:text-gray-900 dark:hover:text-white transition-colors">{{ app()->getLocale() == 'en' ? 'Terms & Conditions' : 'Conditions générales' }}</a></li>
                    <li><a href="{{ route('pages.about') }}" class="hover:text-gray-900 dark:hover:text-white transition-colors">{{ app()->getLocale() == 'en' ? 'Cookies Policy' : 'Politique de cookies' }}</a></li>
                    <li><a href="{{ route('pages.about') }}" class="hover:text-gray-900 dark:hover:text-white transition-colors">{{ app()->getLocale() == 'en' ? 'Prohibited Items' : 'Articles interdits' }}</a></li>
                </ul>
            </div>

            {{-- Support --}}
            <div>
                <p class="font-bold text-[10px] uppercase tracking-widest text-gray-800 dark:text-white mb-3">
                    {{ app()->getLocale() == 'en' ? 'Support' : 'Assistance' }}
                </p>
                <ul class="space-y-1.5">
                    <li><a href="{{ route('pages.contact') }}" class="text-red-500 dark:text-red-400 hover:text-red-600 dark:hover:text-red-300 font-semibold transition-colors">{{ app()->getLocale() == 'en' ? 'Contact Us' : 'Nous contacter' }}</a></li>
                    <li><a href="{{ route('pages.about') }}" class="hover:text-gray-900 dark:hover:text-white transition-colors">{{ app()->getLocale() == 'en' ? 'Security Tips' : 'Conseils de sécurité' }}</a></li>
                    <li><a href="{{ route('pages.about') }}" class="hover:text-gray-900 dark:hover:text-white transition-colors">FAQ</a></li>
                    <li><a href="{{ route('pages.about') }}" class="text-amber-500 dark:text-amber-400 hover:text-amber-600 dark:hover:text-amber-300 font-semibold transition-colors">{{ app()->getLocale() == 'en' ? 'Avoid Scams' : 'Éviter les arnaques' }}</a></li>
                </ul>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="pt-5 flex flex-col sm:flex-row justify-between items-center gap-3 text-[11px] text-gray-400 dark:text-gray-500">
            <div class="flex flex-wrap items-center gap-3">
                <span class="text-gray-500 dark:text-gray-400">© {{ date('Y') }} SafeMarket. {{ app()->getLocale() == 'en' ? 'All rights reserved.' : 'Tous droits réservés.' }}</span>
                <span class="hidden sm:inline text-gray-300 dark:text-gray-700">·</span>
                <a href="{{ route('pages.about') }}" class="hover:text-gray-900 dark:hover:text-gray-300 transition-colors">{{ app()->getLocale() == 'en' ? 'Privacy' : 'Confidentialité' }}</a>
                <span class="text-gray-300 dark:text-gray-700">·</span>
                <a href="{{ route('pages.about') }}" class="hover:text-gray-900 dark:hover:text-gray-300 transition-colors">{{ app()->getLocale() == 'en' ? 'Terms' : 'Conditions' }}</a>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                {{-- Language Switcher --}}
                <div class="flex items-center gap-1.5 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-lg px-2.5 py-1">
                    <a href="{{ route('lang.switch', 'en') }}"
                       class="font-bold transition-colors {{ app()->getLocale() == 'en' ? 'text-red-500 dark:text-red-400' : 'text-gray-400 hover:text-gray-700 dark:text-gray-500 dark:hover:text-gray-300' }}">EN</a>
                    <span class="text-gray-300 dark:text-gray-700">|</span>
                    <a href="{{ route('lang.switch', 'fr') }}"
                       class="font-bold transition-colors {{ app()->getLocale() == 'fr' ? 'text-red-500 dark:text-red-400' : 'text-gray-400 hover:text-gray-700 dark:text-gray-500 dark:hover:text-gray-300' }}">FR</a>
                </div>

                {{-- Payment Badges --}}
                <div class="flex items-center gap-1.5">
                    <span class="px-2 py-1 rounded-md bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 text-amber-500 dark:text-amber-400 font-bold text-[10px]">MTN MoMo</span>
                    <span class="px-2 py-1 rounded-md bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 text-orange-500 dark:text-orange-400 font-bold text-[10px]">Orange Money</span>
                    <span class="px-2 py-1 rounded-md bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 text-emerald-600 dark:text-emerald-400 font-bold text-[10px]">Campay</span>
                </div>
            </div>
        </div>
    </div>
</footer>

{{-- Scroll-To-Top Button --}}
<button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="fixed bottom-5 right-5 p-3 bg-red-600 hover:bg-red-700 text-white rounded-2xl shadow-xl shadow-red-600/30 transition-all duration-200 hover:-translate-y-1 z-30" title="Remonter en haut">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/>
    </svg>
</button>
