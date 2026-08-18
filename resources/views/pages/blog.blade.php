<x-safemarket-layout>
    <x-slot name="title">{{ __('Actualités & Blog - SafeMarket') }}</x-slot>

    <!-- Blog Header Banner -->
    <div class="relative bg-zinc-900 py-16 text-white overflow-hidden">
        <div class="absolute inset-0 opacity-15 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px] pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 space-y-4">
            <span class="text-red-500 font-heading font-black tracking-widest text-xs uppercase block">{{ app()->getLocale() == 'en' ? 'SafeMarket Magazine' : 'Magazine SafeMarket' }}</span>
            <h1 class="font-heading font-black text-4xl sm:text-5xl uppercase leading-none tracking-tight">
                {{ app()->getLocale() == 'en' ? 'STREETWEAR & SECURE TECH NEWS' : "L'ACTUALITÉ STREETWEAR & SECURE TECH" }}
            </h1>
            <p class="text-stone-300 text-sm max-w-xl mx-auto leading-relaxed">
                {{ app()->getLocale() == 'en' ? 'Trend analysis, certified refurbished purchase guides, and security tips for Central Africa.' : 'Décryptages des tendances, guides d\'achat de produits reconditionnés et conseils de sécurité pour vos transactions en Afrique Centrale.' }}
            </p>
        </div>
    </div>

    <!-- Blog Posts Grid -->
    <div class="py-12 bg-stone-50 dark:bg-zinc-950 min-h-[500px]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Article 1 -->
                <div class="bg-white dark:bg-zinc-900 rounded-[2rem] border border-stone-200/50 dark:border-zinc-800/80 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                    <div class="relative h-48 bg-stone-150 overflow-hidden">
                        <img src="{{ asset('images/striz/hero.jpg') }}" alt="{{ __('Streetwear Guide') }}" class="w-full h-full object-cover">
                        <span class="absolute top-4 left-4 px-2.5 py-1 text-[8px] font-black uppercase tracking-widest bg-red-600 text-white rounded">
                            {{ app()->getLocale() == 'en' ? 'STYLE GUIDE' : 'GUIDE STYLE' }}
                        </span>
                    </div>
                    <div class="p-6 flex-grow flex flex-col justify-between">
                        <div class="space-y-3">
                            <span class="text-[10px] text-stone-400 font-bold uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'August 7, 2026 • 5 min read' : '7 Août 2026 • 5 min de lecture' }}</span>
                            <h3 class="font-heading font-extrabold text-lg text-stone-950 dark:text-white uppercase leading-snug hover:text-red-650 transition-colors">
                                <a href="#">{{ app()->getLocale() == 'en' ? 'Must-have streetwear pieces for this summer in Central Africa' : 'Les pièces streetwear indispensables pour cet été en Afrique Centrale' }}</a>
                            </h3>
                            <p class="text-xs text-stone-500 dark:text-zinc-400 leading-relaxed">
                                {{ app()->getLocale() == 'en' ? 'From light oversized hoodies to tie-dye graphic tees, discover our exclusive selection of original items from reputable brands.' : 'Du hoodie oversize léger au t-shirt graphique tie-dye, découvrez notre sélection exclusive de vêtements originaux de marques réputées.' }}
                            </p>
                        </div>
                        <div class="flex items-center gap-3 pt-6 mt-6 border-t border-stone-100 dark:border-zinc-800/85">
                            <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center font-heading font-black text-xs text-red-600">ST</div>
                            <div>
                                <p class="text-[10px] font-black uppercase text-stone-900 dark:text-white">{{ __('SafeMarket Editor') }}</p>
                                <p class="text-[9px] text-stone-400">{{ app()->getLocale() == 'en' ? 'Stylist & Writer' : 'Styliste & Rédacteur' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Article 2 -->
                <div class="bg-white dark:bg-zinc-900 rounded-[2rem] border border-stone-200/50 dark:border-zinc-800/80 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                    <div class="relative h-48 bg-stone-150 overflow-hidden">
                        <img src="{{ asset('images/products/macbook.jpg') }}" alt="{{ __('Tech Check') }}" class="w-full h-full object-cover">
                        <span class="absolute top-4 left-4 px-2.5 py-1 text-[8px] font-black uppercase tracking-widest bg-indigo-600 text-white rounded">
                            {{ app()->getLocale() == 'en' ? 'TECH ADVICE' : 'CONSEIL TECH' }}
                        </span>
                    </div>
                    <div class="p-6 flex-grow flex flex-col justify-between">
                        <div class="space-y-3">
                            <span class="text-[10px] text-stone-400 font-bold uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'August 6, 2026 • 4 min read' : '6 Août 2026 • 4 min de lecture' }}</span>
                            <h3 class="font-heading font-extrabold text-lg text-stone-950 dark:text-white uppercase leading-snug hover:text-red-650 transition-colors">
                                <a href="#">{{ app()->getLocale() == 'en' ? 'How to buy a laptop or smartphone without risk of scams' : "Comment acheter un ordinateur portable ou smartphone sans risque d'arnaque" }}</a>
                            </h3>
                            <p class="text-xs text-stone-500 dark:text-zinc-400 leading-relaxed">
                                {{ app()->getLocale() == 'en' ? 'Our step-by-step guide to inspect an online seller\'s tech device and the utility of escrow payment.' : 'Notre guide étape par étape pour inspecter l\'appareil électronique d\'un vendeur en ligne et l\'utilité du paiement par séquestre.' }}
                            </p>
                        </div>
                        <div class="flex items-center gap-3 pt-6 mt-6 border-t border-stone-100 dark:border-zinc-800/85">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center font-heading font-black text-xs text-indigo-600">SM</div>
                            <div>
                                <p class="text-[10px] font-black uppercase text-stone-900 dark:text-white">{{ __('Escrow Specialist') }}</p>
                                <p class="text-[9px] text-stone-400">{{ app()->getLocale() == 'en' ? 'Security Analyst' : 'Analyste de sécurité' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Article 3 -->
                <div class="bg-white dark:bg-zinc-900 rounded-[2rem] border border-stone-200/50 dark:border-zinc-800/80 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                    <div class="relative h-48 bg-stone-150 overflow-hidden">
                        <img src="{{ asset('images/products/nike.jpg') }}" alt="{{ __('Sneakers drop') }}" class="w-full h-full object-cover">
                        <span class="absolute top-4 left-4 px-2.5 py-1 text-[8px] font-black uppercase tracking-widest bg-amber-600 text-white rounded">
                            {{ __('SNEAKER NEWS') }}
                        </span>
                    </div>
                    <div class="p-6 flex-grow flex flex-col justify-between">
                        <div class="space-y-3">
                            <span class="text-[10px] text-stone-400 font-bold uppercase tracking-widest">{{ app()->getLocale() == 'en' ? 'August 4, 2026 • 6 min read' : '4 Août 2026 • 6 min de lecture' }}</span>
                            <h3 class="font-heading font-extrabold text-lg text-stone-950 dark:text-white uppercase leading-snug hover:text-red-650 transition-colors">
                                <a href="#">{{ app()->getLocale() == 'en' ? 'The original sneaker market in Cameroon: decryption of the craze' : 'Le marché de la sneaker originale au Cameroun : décryptage de l\'engouement' }}</a>
                            </h3>
                            <p class="text-xs text-stone-500 dark:text-zinc-400 leading-relaxed">
                                {{ app()->getLocale() == 'en' ? 'Why original pairs are sold more and more via escrow, and how to spot fakes using details.' : 'Pourquoi les paires originales se vendent de plus en plus via escrow, et comment repérer les contrefaçons grâce aux détails.' }}
                            </p>
                        </div>
                        <div class="flex items-center gap-3 pt-6 mt-6 border-t border-stone-100 dark:border-zinc-800/85">
                            <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center font-heading font-black text-xs text-amber-600">JD</div>
                            <div>
                                <p class="text-[10px] font-black uppercase text-stone-900 dark:text-white">Jean Dupont</p>
                                <p class="text-[9px] text-stone-400">{{ app()->getLocale() == 'en' ? 'Sneaker Expert' : 'Expert Sneakers' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-safemarket-layout>
