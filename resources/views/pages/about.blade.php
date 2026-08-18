<x-safemarket-layout>
    <x-slot name="title">{{ __('À propos - SafeMarket') }}</x-slot>

    <!-- About Hero Header -->
    <div class="relative bg-zinc-900 py-16 text-white overflow-hidden">
        <div class="absolute inset-0 opacity-15 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px] pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 space-y-4">
            <span class="text-red-500 font-heading font-black tracking-widest text-xs uppercase block">{{ app()->getLocale() == 'en' ? 'About Us' : 'À propos' }}</span>
            <h1 class="font-heading font-black text-4xl sm:text-5xl uppercase leading-none tracking-tight">
                {{ app()->getLocale() == 'en' ? 'THE SAFEMARKET STORY' : "L'HISTOIRE DE SAFEMARKET" }}
            </h1>
            <p class="text-stone-300 text-sm max-w-xl mx-auto leading-relaxed">
                {{ app()->getLocale() == 'en' ? 'A unique alliance between authentic urban streetwear culture and cutting-edge financial escrow technology to secure trusted transactions.' : 'Une alliance unique entre la culture streetwear urbaine authentique et la technologie d\'escrow financier de pointe pour sécuriser les transactions de confiance.' }}
            </p>
        </div>
    </div>

    <!-- Main Content Section -->
    <div class="py-16 bg-stone-50 dark:bg-zinc-950 text-stone-850 dark:text-zinc-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
            <!-- Grid: Story & Vision -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="space-y-6">
                    <h2 class="font-heading font-black text-3xl text-stone-900 dark:text-white uppercase leading-tight">
                        {{ app()->getLocale() == 'en' ? 'OUR MISSION: SECURE THE BUY OF YOUR DREAMS' : "NOTRE MISSION : SÉCURISER L'ACHAT DE VOS RÊVES" }}
                    </h2>
                    <p class="text-sm text-stone-600 dark:text-zinc-400 leading-relaxed">
                        {{ app()->getLocale() == 'en' ? 'Born from the desire to connect enthusiasts of brand sneakers and original tech equipment in Central Africa, SafeMarket has developed a secure platform to completely eliminate the risk of scamming.' : 'Né de la volonté de connecter les passionnés de sneakers de marque et d\'équipements technologiques originaux en Afrique Centrale, SafeMarket a développé une plateforme sécurisée pour éliminer complètement le risque d\'escroquerie.' }}
                    </p>
                    <p class="text-sm text-stone-600 dark:text-zinc-400 leading-relaxed">
                        {{ app()->getLocale() == 'en' ? 'With our SafeMarket marketplace, we bring a rigorous and exclusive selection of authentic fashion pieces. Each seller registering on our portal must validate a strict KYC (identity verification) manually checked by our moderators.' : 'Avec notre marketplace **SafeMarket**, nous apportons une sélection rigoureuse et exclusive de pièces de mode authentiques. Chaque vendeur s\'inscrivant sur notre portail doit valider un KYC strict (vérification d\'identité) validé manuellement par nos modérateurs.' }}
                    </p>
                </div>
                <div class="relative rounded-3xl overflow-hidden shadow-2xl border-4 border-stone-200 dark:border-zinc-850 aspect-video">
                    <img src="{{ asset('images/striz/right.jpg') }}" alt="{{ __('Streetwear Culture About') }}" class="w-full h-full object-cover">
                </div>
            </div>

            <!-- Grid: Values (4 Columns) -->
            <div class="space-y-8">
                <div class="text-center">
                    <h3 class="font-heading font-black text-2xl text-stone-900 dark:text-white uppercase tracking-wider">{{ app()->getLocale() == 'en' ? 'OUR KEY VALUES' : 'NOS VALEURS CLÉS' }}</h3>
                    <div class="w-16 h-1 bg-red-600 mx-auto mt-2"></div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Value 1 -->
                    <div class="bg-white dark:bg-zinc-900 border border-stone-200/50 dark:border-zinc-800/80 rounded-2xl p-6 shadow-sm hover:shadow-lg transition">
                        <span class="text-3xl">🛡️</span>
                        <h4 class="font-heading font-black text-sm uppercase tracking-wider text-stone-900 dark:text-white mt-4 mb-2">{{ app()->getLocale() == 'en' ? 'Escrow Payment' : 'Paiement Escrow' }}</h4>
                        <p class="text-xs text-stone-500 dark:text-zinc-400 leading-relaxed">
                            {{ app()->getLocale() == 'en' ? "The buyer's money remains secured by SafeMarket and is only paid to the seller when the buyer validates receipt." : "L'argent de l'acheteur reste sécurisé par SafeMarket et n'est versé au vendeur que lorsque l'acheteur valide la bonne réception." }}
                        </p>
                    </div>

                    <!-- Value 2 -->
                    <div class="bg-white dark:bg-zinc-900 border border-stone-200/50 dark:border-zinc-800/80 rounded-2xl p-6 shadow-sm hover:shadow-lg transition">
                        <span class="text-3xl">👤</span>
                        <h4 class="font-heading font-black text-sm uppercase tracking-wider text-stone-900 dark:text-white mt-4 mb-2">{{ app()->getLocale() == 'en' ? 'KYC Verified Sellers' : 'Vendeurs KYC Validés' }}</h4>
                        <p class="text-xs text-stone-500 dark:text-zinc-400 leading-relaxed">
                            {{ app()->getLocale() == 'en' ? 'All our merchants undergo a strict check of their ID document to guarantee network honesty and transparency.' : 'Tous nos commerçants passent une vérification stricte de leur pièce d\'identité pour garantir l\'honnêteté et la transparence du réseau.' }}
                        </p>
                    </div>

                    <!-- Value 3 -->
                    <div class="bg-white dark:bg-zinc-900 border border-stone-200/50 dark:border-zinc-800/80 rounded-2xl p-6 shadow-sm hover:shadow-lg transition">
                        <span class="text-3xl">✨</span>
                        <h4 class="font-heading font-black text-sm uppercase tracking-wider text-stone-900 dark:text-white mt-4 mb-2">{{ app()->getLocale() == 'en' ? 'Original Quality' : 'Qualité Originale' }}</h4>
                        <p class="text-xs text-stone-500 dark:text-zinc-400 leading-relaxed">
                            {{ app()->getLocale() == 'en' ? 'A selection of high-end electronic devices and exclusive sneakers from global brands of controlled origin.' : 'Une sélection d\'appareils électroniques haut de gamme et de baskets exclusives de marques mondiales d\'origine contrôlée.' }}
                        </p>
                    </div>

                    <!-- Value 4 -->
                    <div class="bg-white dark:bg-zinc-900 border border-stone-200/50 dark:border-zinc-800/80 rounded-2xl p-6 shadow-sm hover:shadow-lg transition">
                        <span class="text-3xl">⚖️</span>
                        <h4 class="font-heading font-black text-sm uppercase tracking-wider text-stone-900 dark:text-white mt-4 mb-2">{{ app()->getLocale() == 'en' ? 'Fair Arbitration' : 'Arbitrage Équitable' }}</h4>
                        <p class="text-xs text-stone-500 dark:text-zinc-400 leading-relaxed">
                            {{ app()->getLocale() == 'en' ? 'In case of disagreement during delivery, our moderation team analyzes the case and arbitrates the dispute impartially.' : 'En cas de désaccord lors de la livraison, notre équipe de modération analyse le dossier et arbitre le litige de manière impartiale.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-safemarket-layout>
