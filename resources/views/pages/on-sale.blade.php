<x-safemarket-layout>
    <x-slot name="title">{{ __('Offres en Promotion - SafeMarket') }}</x-slot>

    <!-- Promo Hero Header -->
    <div class="relative bg-gradient-to-r from-red-650 via-amber-600 to-red-750 py-16 text-white overflow-hidden"
         x-data="{
             days: 0, hours: 0, minutes: 0, seconds: 0,
             init() {
                 let target = new Date().getTime() + (3 * 24 * 60 * 60 * 1000); // 3 days from now
                 setInterval(() => {
                     let now = new Date().getTime();
                     let diff = target - now;
                     this.days = Math.floor(diff / (1000 * 60 * 60 * 24));
                     this.hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                     this.minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                     this.seconds = Math.floor((diff % (1000 * 60)) / 1000);
                 }, 1000);
             }
         }">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px] pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="space-y-4">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/20 rounded-full text-xs font-bold uppercase tracking-wider">
                     {{ app()->getLocale() == 'en' ? '🔥 Flash Deals' : '🔥 Offres Flash' }}
                </span>
                <h1 class="font-heading font-black text-4xl sm:text-5xl uppercase leading-none">
                    SAFEMARKET <span class="italic text-yellow-300">{{ __('SALE DROP') }}</span>
                </h1>
                <p class="text-sm text-red-50 max-w-md leading-relaxed">
                    {{ app()->getLocale() == 'en' ? 'The best deals on streetwear clothing and certified tech devices. All payments are secured by SafeMarket escrow.' : 'Les meilleures offres de vêtements de rue et de produits électroniques à prix réduit. Toutes les transactions sont sécurisées par le séquestre SafeMarket.' }}
                </p>
            </div>
            
            <!-- Countdown Timer Card -->
            <div class="bg-black/25 backdrop-blur-md border border-white/10 rounded-3xl p-6 text-center shrink-0 min-w-[280px]">
                <p class="text-[10px] uppercase tracking-widest font-black text-yellow-300 mb-3">{{ app()->getLocale() == 'en' ? 'Promotion ends in:' : 'La promotion se termine dans :' }}</p>
                <div class="grid grid-cols-4 gap-3 text-white">
                    <div>
                        <span class="block text-2xl font-black font-heading" x-text="days">0</span>
                        <span class="text-[9px] uppercase font-bold text-red-200">{{ app()->getLocale() == 'en' ? 'Days' : 'Jours' }}</span>
                    </div>
                    <div>
                        <span class="block text-2xl font-black font-heading" x-text="hours">0</span>
                        <span class="text-[9px] uppercase font-bold text-red-200">{{ app()->getLocale() == 'en' ? 'Hours' : 'Heures' }}</span>
                    </div>
                    <div>
                        <span class="block text-2xl font-black font-heading" x-text="minutes">0</span>
                        <span class="text-[9px] uppercase font-bold text-red-200">Min</span>
                    </div>
                    <div>
                        <span class="block text-2xl font-black font-heading" x-text="seconds">0</span>
                        <span class="text-[9px] uppercase font-bold text-red-200">Sec</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- On Sale Products Grid -->
    <div class="py-12 bg-stone-50 dark:bg-zinc-950 min-h-[500px]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <span class="text-red-500 font-heading font-black tracking-widest text-[10px] uppercase block">{{ __('Selected items') }}</span>
                <h2 class="font-heading text-2xl font-extrabold uppercase dark:text-white">{{ app()->getLocale() == 'en' ? 'Items On Sale' : 'Articles En Promotion' }}</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($products as $product)
                    <div class="group flex flex-col bg-white dark:bg-zinc-900 rounded-[2rem] border border-stone-200/60 dark:border-zinc-800/80 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300">
                        <!-- Image Container -->
                        <div class="relative h-64 bg-stone-100 dark:bg-zinc-950 overflow-hidden flex items-center justify-center">
                            <img src="{{ $product->getImageUrl() }}" alt="{{ $product->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
                            
                            <!-- Badges -->
                            <div class="absolute top-4 left-4 flex flex-col gap-2">
                                <span class="px-2.5 py-1 text-[9px] font-black uppercase tracking-widest bg-red-600 text-white rounded shadow-sm">
                                    {{ __('-15% OFF') }}
                                </span>
                            </div>

                            <!-- Quick View overlay -->
                            <div class="absolute inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                @auth
                                    <a href="{{ route('products.show', $product) }}" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-heading font-black text-[10px] tracking-widest uppercase rounded shadow transition">
                                        {{ app()->getLocale() == 'en' ? 'View offer' : 'Voir l\'offre' }}
                                    </a>
                                @else
                                    <button @click="showAuthModal = true" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-heading font-black text-[10px] tracking-widest uppercase rounded shadow transition">
                                        {{ app()->getLocale() == 'en' ? 'View offer' : 'Voir l\'offre' }}
                                    </button>
                                @endauth
                            </div>
                        </div>

                        <!-- Info details -->
                        <div class="p-6 flex-grow flex flex-col justify-between">
                            <div class="space-y-1">
                                <span class="text-[10px] text-stone-400 dark:text-zinc-500 font-bold uppercase tracking-widest">{{ $product->category->name }}</span>
                                <h3 class="font-heading font-extrabold text-base text-stone-950 dark:text-white uppercase leading-tight group-hover:text-red-650 transition-colors">
                                    @auth
                                        <a href="{{ route('products.show', $product) }}">{{ $product->title }}</a>
                                    @else
                                        <a href="javascript:void(0)" @click="showAuthModal = true">{{ $product->title }}</a>
                                    @endauth
                                </h3>
                            </div>
                            <div class="flex justify-between items-center mt-4 pt-4 border-t border-stone-100 dark:border-zinc-800/80">
                                <div class="flex flex-col">
                                    <span class="text-xs text-stone-400 dark:text-zinc-500 line-through">{{ number_format($product->price * 1.15, 0) }} FCFA</span>
                                    <span class="text-lg font-black text-red-600 dark:text-red-500">{{ number_format($product->price, 0) }} FCFA</span>
                                </div>
                                <span class="text-xs px-2.5 py-1 bg-red-50 dark:bg-red-950/20 text-red-600 dark:text-red-400 font-bold rounded-lg border border-red-200/50 dark:border-red-900/30">
                                    {{ app()->getLocale() == 'en' ? 'Condition' : 'État' }}: {{ $product->condition }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-safemarket-layout>
